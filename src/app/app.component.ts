import { OneSignal } from "@ionic-native/onesignal/ngx";
import { Component } from "@angular/core";

import { Platform } from "@ionic/angular";
import { SplashScreen } from "@ionic-native/splash-screen/ngx";
import { StatusBar } from "@ionic-native/status-bar/ngx";

@Component({
  selector: "app-root",
  templateUrl: "app.component.html",
  styleUrls: ["app.component.scss"],
})
export class AppComponent {
  showSplash = true;
  constructor(
    private platform: Platform,
    private splashScreen: SplashScreen,
    private statusBar: StatusBar,
    private oneSignal: OneSignal
  ) {
    this.initializeApp();
  }

  initializeApp() {
    this.platform.ready().then(() => {
      //este codigo es para desabiitar el back button en toda la app
      document.addEventListener(
        "backbutton",
        function (e) {
          console.log("disabled");
        },
        false
      );

      this.oneSignal
        .startInit("74576420-bf38-4e67-b402-a547e2bb8bd8", "190542733909")
        .inFocusDisplaying(this.oneSignal.OSInFocusDisplayOption.Notification)
        .endInit();
      this.oneSignal.getIds().then((identity) => {
        console.log(identity);
        localStorage.setItem("pushToken", identity.pushToken);
        localStorage.setItem("pushIdToken", identity.userId);
      });
      this.statusBar.styleDefault();
      this.splashScreen.hide();
    });
  }
}
