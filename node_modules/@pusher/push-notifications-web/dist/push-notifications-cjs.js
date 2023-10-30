'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};
    var ownKeys = Object.keys(source);

    if (typeof Object.getOwnPropertySymbols === 'function') {
      ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {
        return Object.getOwnPropertyDescriptor(source, sym).enumerable;
      }));
    }

    ownKeys.forEach(function (key) {
      _defineProperty(target, key, source[key]);
    });
  }

  return target;
}

function doRequest({
  method,
  path,
  body = null,
  headers = {}
}) {
  const options = {
    method,
    headers
  };

  if (body !== null) {
    options.body = JSON.stringify(body);
    options.headers = _objectSpread({
      'Content-Type': 'application/json'
    }, headers);
  }

  return fetch(path, options).then(async response => {
    if (!response.ok) {
      await handleError(response);
    }

    try {
      return await response.json();
    } catch (_) {
      return null;
    }
  });
}

async function handleError(response) {
  let errorMessage;

  try {
    const {
      error = 'Unknown error',
      description = 'No description'
    } = await response.json();
    errorMessage = `Unexpected status code ${response.status}: ${error}, ${description}`;
  } catch (_) {
    errorMessage = `Unexpected status code ${response.status}: Cannot parse error response`;
  }

  throw new Error(errorMessage);
}

class TokenProvider {
  constructor({
    url,
    queryParams,
    headers
  } = {}) {
    this.url = url;
    this.queryParams = queryParams;
    this.headers = headers;
  }

  async fetchToken(userId) {
    let queryParams = _objectSpread({
      user_id: userId
    }, this.queryParams);

    const encodedParams = Object.entries(queryParams).map(kv => kv.map(encodeURIComponent).join('=')).join('&');
    const options = {
      method: 'GET',
      path: `${this.url}?${encodedParams}`,
      headers: this.headers
    };
    let response = await doRequest(options);
    return response;
  }

}

class DeviceStateStore {
  constructor(instanceId) {
    this._instanceId = instanceId;
    this._dbConn = null;
  }

  get _dbName() {
    return `beams-${this._instanceId}`;
  }

  get isConnected() {
    return this._dbConn !== null;
  }

  connect() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this._dbName);

      request.onsuccess = event => {
        const db = event.target.result;
        this._dbConn = db;

        this._readState().then(state => state === null ? this.clear() : Promise.resolve()).then(resolve);
      };

      request.onupgradeneeded = event => {
        const db = event.target.result;
        db.createObjectStore('beams', {
          keyPath: 'instance_id'
        });
      };

      request.onerror = event => {
        const error = new Error(`Database error: ${event.target.error}`);
        reject(error);
      };
    });
  }

  clear() {
    return this._writeState({
      instance_id: this._instanceId,
      device_id: null,
      token: null,
      user_id: null
    });
  }

  _readState() {
    if (!this.isConnected) {
      throw new Error('Cannot read value: DeviceStateStore not connected to IndexedDB');
    }

    return new Promise((resolve, reject) => {
      const request = this._dbConn.transaction('beams').objectStore('beams').get(this._instanceId);

      request.onsuccess = event => {
        const state = event.target.result;

        if (!state) {
          resolve(null);
        }

        resolve(state);
      };

      request.onerror = event => {
        reject(event.target.error);
      };
    });
  }

  async _readProperty(name) {
    const state = await this._readState();

    if (state === null) {
      return null;
    }

    return state[name] || null;
  }

  _writeState(state) {
    if (!this.isConnected) {
      throw new Error('Cannot write value: DeviceStateStore not connected to IndexedDB');
    }

    return new Promise((resolve, reject) => {
      const request = this._dbConn.transaction('beams', 'readwrite').objectStore('beams').put(state);

      request.onsuccess = _ => {
        resolve();
      };

      request.onerror = event => {
        reject(event.target.error);
      };
    });
  }

  async _writeProperty(name, value) {
    const state = await this._readState();
    state[name] = value;
    await this._writeState(state);
  }

  getToken() {
    return this._readProperty('token');
  }

  setToken(token) {
    return this._writeProperty('token', token);
  }

  getDeviceId() {
    return this._readProperty('device_id');
  }

  setDeviceId(deviceId) {
    return this._writeProperty('device_id', deviceId);
  }

  getUserId() {
    return this._readProperty('user_id');
  }

  setUserId(userId) {
    return this._writeProperty('user_id', userId);
  }

  getLastSeenSdkVersion() {
    return this._readProperty('last_seen_sdk_version');
  }

  setLastSeenSdkVersion(sdkVersion) {
    return this._writeProperty('last_seen_sdk_version', sdkVersion);
  }

  getLastSeenUserAgent() {
    return this._readProperty('last_seen_user_agent');
  }

  setLastSeenUserAgent(userAgent) {
    return this._writeProperty('last_seen_user_agent', userAgent);
  }

}

var version = "0.9.1";

const SERVICE_WORKER_URL = `/service-worker.js?pusherBeamsWebSDKVersion=${version}`;
async function init(config) {
  if (!config) {
    throw new Error('Config object required');
  }

  const {
    instanceId,
    endpointOverride = null,
    serviceWorkerRegistration = null
  } = config;

  if (instanceId === undefined) {
    throw new Error('Instance ID is required');
  }

  if (typeof instanceId !== 'string') {
    throw new Error('Instance ID must be a string');
  }

  if (instanceId.length === 0) {
    throw new Error('Instance ID cannot be empty');
  }

  if (!window.indexedDB) {
    throw new Error('Pusher Beams does not support this browser version (IndexedDB not supported)');
  }

  if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
    throw new Error('Pusher Beams does not support this browser version (ServiceWorkerRegistration not supported)');
  }

  if (!('PushManager' in window)) {
    throw new Error('Pusher Beams does not support this browser version (PushManager not supported)');
  }

  const deviceStateStore = new DeviceStateStore(instanceId);
  await deviceStateStore.connect();
  const deviceId = await deviceStateStore.getDeviceId();
  const token = await deviceStateStore.getToken();
  const userId = await deviceStateStore.getUserId();
  const instance = new PushNotificationsInstance({
    instanceId,
    deviceId,
    token,
    userId,
    serviceWorkerRegistration,
    deviceStateStore,
    endpointOverride
  });
  const deviceExists = deviceId !== null;

  if (deviceExists) {
    try {
      await instance._updateDeviceMetadata();
    } catch (_) {// Best effort, do nothing if this fails.
    }
  }

  return instance;
}

class PushNotificationsInstance {
  constructor({
    instanceId,
    deviceId,
    token,
    userId,
    serviceWorkerRegistration,
    deviceStateStore,
    endpointOverride = null
  }) {
    this.instanceId = instanceId;
    this.deviceId = deviceId;
    this.token = token;
    this.userId = userId;
    this._deviceStateStore = deviceStateStore;
    this._endpoint = endpointOverride; // Internal only

    if (serviceWorkerRegistration) {
      const serviceWorkerScope = serviceWorkerRegistration.scope;
      const currentURL = window.location.href;
      const scopeMatchesCurrentPage = currentURL.startsWith(serviceWorkerScope);

      if (!scopeMatchesCurrentPage) {
        throw new Error(`Could not initialize Pusher web push: current page not in serviceWorkerRegistration scope (${serviceWorkerScope})`);
      }
    }

    this._serviceWorkerRegistration = serviceWorkerRegistration;
  }

  get _baseURL() {
    if (this._endpoint !== null) {
      return this._endpoint;
    }

    return `https://${this.instanceId}.pushnotifications.pusher.com`;
  }

  async start() {
    // Temporary whilst we only support Chrome in Beta release
    if (!isSupportedBrowser()) {
      console.warn('Pusher Web Push Notifications only supports Google Chrome (whilst in Beta)');
      return this;
    }

    if (this.deviceId !== null) {
      return this;
    }

    const {
      vapidPublicKey: publicKey
    } = await this._getPublicKey(); // register with pushManager, get endpoint etc

    const token = await this._getPushToken(publicKey); // get device id from errol

    const deviceId = await this._registerDevice(token);
    await this._deviceStateStore.setToken(token);
    await this._deviceStateStore.setDeviceId(deviceId);
    await this._deviceStateStore.setLastSeenSdkVersion(version);
    await this._deviceStateStore.setLastSeenUserAgent(window.navigator.userAgent);
    this.token = token;
    this.deviceId = deviceId;
    return this;
  }

  async setUserId(userId, tokenProvider) {
    // Temporary whilst we only support Chrome in Beta release
    if (!isSupportedBrowser()) {
      console.warn('Pusher Web Push Notifications only supports Google Chrome (whilst in Beta)');
      return;
    }

    if (this.deviceId === null) {
      const error = new Error('.start must be called before .setUserId');
      return Promise.reject(error);
    }

    if (typeof userId !== 'string') {
      throw new Error(`User ID must be a string (was ${userId})`);
    }

    if (userId === '') {
      throw new Error('User ID cannot be the empty string');
    }

    if (this.userId !== null && this.userId !== userId) {
      throw new Error('Changing the `userId` is not allowed.');
    }

    const path = `${this._baseURL}/device_api/v1/instances/${encodeURIComponent(this.instanceId)}/devices/web/${this.deviceId}/user`;
    const {
      token: beamsAuthToken
    } = await tokenProvider.fetchToken(userId);
    const options = {
      method: 'PUT',
      path,
      headers: {
        Authorization: `Bearer ${beamsAuthToken}`
      }
    };
    await doRequest(options);
    this.userId = userId;
    return this._deviceStateStore.setUserId(userId);
  }

  async stop() {
    // Temporary whilst we only support Chrome in Beta release
    if (!isSupportedBrowser()) {
      console.warn('Pusher Web Push Notifications only supports Google Chrome (whilst in Beta)');
      return;
    }

    if (this.deviceId === null) {
      return;
    }

    await this._deleteDevice();
    await this._deviceStateStore.clear();
    this.deviceId = null;
    this.token = null;
    this.userId = null;
  }

  async clearAllState() {
    // Temporary whilst we only support Chrome in Beta release
    if (!isSupportedBrowser()) {
      console.warn('Pusher Web Push Notifications only supports Google Chrome (whilst in Beta)');
      return;
    }

    await this.stop();
    await this.start();
  }

  async _getPublicKey() {
    const path = `${this._baseURL}/device_api/v1/instances/${encodeURIComponent(this.instanceId)}/web-vapid-public-key`;
    const options = {
      method: 'GET',
      path
    };
    return doRequest(options);
  }

  async _getPushToken(publicKey) {
    try {
      let reg;

      if (this._serviceWorkerRegistration) {
        reg = this._serviceWorkerRegistration; // TODO: Call update only when we detect an SDK change
      } else {
        // Check that service worker file exists
        const {
          status: swStatusCode
        } = await fetch(SERVICE_WORKER_URL);

        if (swStatusCode !== 200) {
          throw new Error('Cannot start SDK, service worker missing: No file found at /service-worker.js');
        }

        window.navigator.serviceWorker.register(SERVICE_WORKER_URL, {
          // explicitly opting out of `importScripts` caching just in case our
          // customers decides to host and serve the imported scripts and
          // accidentally set `Cache-Control` to something other than `max-age=0`
          updateViaCache: 'none'
        });
        reg = await window.navigator.serviceWorker.ready;
      }

      const sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUInt8Array(publicKey)
      });
      return btoa(JSON.stringify(sub));
    } catch (e) {
      return Promise.reject(e);
    }
  }

  async _registerDevice(token) {
    const path = `${this._baseURL}/device_api/v1/instances/${encodeURIComponent(this.instanceId)}/devices/web`;
    const device = {
      token,
      metadata: {
        sdkVersion: version
      }
    };
    const options = {
      method: 'POST',
      path,
      body: device
    };
    const response = await doRequest(options);
    return response.id;
  }

  async _deleteDevice() {
    const path = `${this._baseURL}/device_api/v1/instances/${encodeURIComponent(this.instanceId)}/devices/web/${encodeURIComponent(this.deviceId)}`;
    const options = {
      method: 'DELETE',
      path
    };
    await doRequest(options);
  }
  /**
   * Submit SDK version and browser details (via the user agent) to Pusher Beams.
   */


  async _updateDeviceMetadata() {
    const userAgent = window.navigator.userAgent;
    const storedUserAgent = await this._deviceStateStore.getLastSeenUserAgent();
    const storedSdkVersion = await this._deviceStateStore.getLastSeenSdkVersion();

    if (userAgent === storedUserAgent && version === storedSdkVersion) {
      // Nothing to do
      return;
    }

    const path = `${this._baseURL}/device_api/v1/instances/${encodeURIComponent(this.instanceId)}/devices/web/${this.deviceId}/metadata`;
    const metadata = {
      sdkVersion: version
    };
    const options = {
      method: 'PUT',
      path,
      body: metadata
    };
    await doRequest(options);
    await this._deviceStateStore.setLastSeenSdkVersion(version);
    await this._deviceStateStore.setLastSeenUserAgent(userAgent);
  }

}

function urlBase64ToUInt8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4);
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
  const rawData = window.atob(base64);
  return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
}
/**
 * Modified from https://stackoverflow.com/questions/4565112
 */


function isSupportedBrowser() {
  const winNav = window.navigator;
  const vendorName = winNav.vendor;
  const isChromium = window.chrome !== null && typeof window.chrome !== 'undefined';
  const isOpera = winNav.userAgent.indexOf('OPR') > -1;
  const isIEedge = winNav.userAgent.indexOf('Edge') > -1;
  const isChrome = isChromium && vendorName === 'Google Inc.' && !isIEedge && !isOpera;
  return isChrome || isOpera;
}

exports.TokenProvider = TokenProvider;
exports.init = init;
