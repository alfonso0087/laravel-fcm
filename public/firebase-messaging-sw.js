/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts("https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"
);

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AAAA_Tlwi58:APA91bHa4ZKNHGDRASMEODc3XehoD0qjMVlRuy4v5M5I5Mj1igPS69f2ao4-PXuRLKMSlgxQD8c1iw1Uqs4aZJ5yTIlmCc7Ph4MxIlaug6yh0uoY4WuRLXs9YWrO8AwYiIQVctPEeruM",
    projectId: "web-notification-80206",
    messagingSenderId: "1087590402975",
    appId: "1:1087590402975:web:e8084769b595c5d6bacbaa",
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});
