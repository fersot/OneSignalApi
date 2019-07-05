// detect when a user clicks a notification.
OneSignal.push(["addListenerForNotificationOpened", function(data) {
    console.log("Received NotificationOpened:");
    console.log(data);
    console.log(data.id);
    console.log(data.heading);
    OneSignal.getUserId( function(userId) {
            console.log("OneSignal User ID:", userId);
            // Make a POST call to GA with the notification data and userId aka playerId
            //ga('send', 'event', 'Notification_Clicked', data.id, userId);
        });
}]);

OneSignal.push(function() {
    // Tracking Subscriptions.

    OneSignal.on('notificationPermissionChange', function(permissionChange) {
      var currentPermission = permissionChange.to;
      console.log('New permission state:', currentPermission);
    //  ga('send', {
      //  hitType: 'event',
     //   eventCategory: 'notification_permission_change',
     //   eventAction: currentPermission
     // });
   });

   //  Tracking Impressions of the opt in request pop-up

     OneSignal.on('permissionPromptDisplay', function(permissionChange) {
    //ga('send', {
     // hitType: 'event',
      //eventCategory: 'notification_prompt',
      //eventAction: 'displayed'
    });
  });