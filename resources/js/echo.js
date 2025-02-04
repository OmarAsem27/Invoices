import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

// window.Echo.private(`App.Models.User.${userId}`).notification(function (data) {
//     // console.log(data);
//     $("#notifications_count").load(
//         window.location.href + " #notifications_count"
//     );
//     $("#unreadNotifications").load(
//         window.location.href + " #unreadNotifications"
//     );
// });

if (userRoles.includes("owner")) {
    window.Echo.private("owner-notifications")
        .notification((notification) => {
            // Refresh notification counts
            $("#notifications_count").load(
                window.location.href + " #notifications_count"
            );
            $("#unreadNotifications").load(
                window.location.href + " #unreadNotifications"
            );
        })
        .subscribed(() => {
            console.log("Connected to owner notifications channel");
        })
        .error((error) => {
            console.error("Channel subscription error:", error);
        });
}
