import './bootstrap';
import '../css/app.css';
import Swal from 'sweetalert2';
import axios from 'axios';

import * as PusherPushNotifications from "@pusher/push-notifications-web";

    // const beamsClient = new PusherPushNotifications.Client({
    //   instanceId: '0958376f-0b36-4f59-adae-c1e55ff3b848',
    // });

    // //Mengambil id dari user yang login untuk dijadikan user_id
    // const user_id = document.getElementById('user_id').value;
    // const beams_token = document.getElementById('beams_token').value;

    // //Mengambil beams_token dari database
    // const tokenProvider = new PusherPushNotifications.TokenProvider({
    //     url: "https://127.0.0.1:8000/beams-generateToken/",
    // });

    // console.log(tokenProvider);

    // beamsClient.start()
    // .then(() => beamsClient.addDeviceInterest("hello"))
    // .then(() => beamsClient.getDeviceInterests())
    // .then((interests) => console.log("Current interests:", interests))

    // .then(() => beamsClient.setUserId(user_id , tokenProvider))
    // .then(() => beamsClient.getDeviceId())
    // .then((userId) => console.log("Successfully registered and identified with Beams. User ID:", userId))
        //Mengirimkan device id ke database

        // .then((beamsClient) => beamsClient.getDeviceId())
        // .then((deviceId) => console.log("Successfully registered with Beams. Device ID:", deviceId))

        // .then(() => beamsClient.setUserId("user-" + user_id , tokenProvider))
        // .then(() => beamsClient.getDeviceId())
        // .then((userId) => console.log("Successfully registered and identified with Beams. User ID:", userId))

        // .then(() => beamsClient.addDeviceInterest("hello"))
        // .then(() => beamsClient.getDeviceInterests())
        // .then((interests) => console.log("Current interests:", interests))
        // .catch(console.error);


