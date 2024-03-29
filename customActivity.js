define(["postmonger"], function (Postmonger) {
  "use strict";

  var connection = new Postmonger.Session();
  var payload = {};
  var campaignCode = '';
  // var lastStepEnabled = false;
  /*var steps = [
    // initialize to the same value as what's set in config.json for consistency
    { label: "Step 1", key: "step1" },
    { label: "Step 2", key: "step2" },
    { label: "Step 3", key: "step3" },
    { label: "Step 4", key: "step4", active: false },
  ];
  var currentStep = steps[0].key;
  */

  connection.on("initActivity", initialize);
  connection.on("requestedTokens", onGetTokens);
  connection.on("requestedEndpoints", onGetEndpoints);

  connection.on("clickedNext", onClickedNext);
  // connection.on("clickedBack", onClickedBack);
  // connection.on("gotoStep", onGotoStep);

  // Trigger ready when window is completely ready
  $(window).ready(onRender);

  function onRender() {
    // JB will respond the first time 'ready' is called with 'initActivity'
    connection.trigger("ready");
    console.log("Ready called");

    $("#campaignCode").on("input", function(){
      campaignCode = $("#campaignCode").val();
      if (campaignCode.length > 0) {
        connection.trigger("updateButton", { button: "next", text: "done", visible: true, enabled: true });
      }else {
        connection.trigger("updateButton", { button: "next", text: "done", visible: true, enabled: false });
      }
  });

    // connection.trigger("requestTokens");
    // connection.trigger("requestEndpoints");

    // Disable the next button if a value isn't selected
    /*$("#select1").change(function () {
      var message = getMessage();
      console.log("message : " + message);
      connection.trigger("updateButton", {
        button: "next",
        enabled: Boolean(message),
      });

      $("#message").html(message);
    }); */

    // Toggle step 4 active/inactive
    // If inactive, wizard hides it and skips over it during navigation
    /*$("#toggleLastStep").click(function () {
      lastStepEnabled = !lastStepEnabled; // toggle status
      steps[3].active = !steps[3].active; // toggle active

      connection.trigger("updateSteps", steps);
    });*/
  }

  function initialize(data) {
    console.log("initialize called");
    if (data) {
      payload = data;
    }
    console.log(payload);

    var campaignCode;
    var hasInArguments = Boolean(
      payload["arguments"] &&
        payload["arguments"].execute &&
        payload["arguments"].execute.inArguments &&
        payload["arguments"].execute.inArguments.length > 0
    );

    var inArguments = hasInArguments
      ? payload["arguments"].execute.inArguments
      : {};

    $.each(inArguments, function (index, inArgument) {
      $.each(inArgument, function (key, val) {
        console.log("Key : " + key);
        console.log("val : " + val);
        if (key === "campaignCode") {
          campaignCode = val;
        }
      });
    });

    // If there is no message selected, disable the next button
    if (!campaignCode) {
      // showStep(null, 1);
      connection.trigger("updateButton", { button: "next", text: "done", visible: true, enabled: false });
      // If there is a message, skip to the summary step
    }else {
      $("#campaignCode").val(campaignCode);
      /*$("#select1")
        .find("option[value=" + message + "]")
        .attr("selected", "selected");
      $("#message").html(message);
      showStep(null, 3);*/
    }
  }

  function onGetTokens(tokens) {
    // Response: tokens = { token: <legacy token>, fuel2token: <fuel api token> }
    console.log("Tokens" + tokens);
  }

  function onGetEndpoints(endpoints) {
    // Response: endpoints = { restHost: <url> } i.e. "rest.s1.qa1.exacttarget.com"
    console.log("Endpoints : " + endpoints);
  }

  function onClickedNext() {
    console.log("Next clicked");
    save();
    /*if (
      (currentStep.key === "step3" && steps[3].active === false) ||
      currentStep.key === "step4"
    ) {
      save();
    } else {
      connection.trigger("nextStep");
    }*/
  }

  function onClickedBack() {
    // connection.trigger("prevStep");
  }

  function onGotoStep(step) {
    // showStep(step);
    // connection.trigger("ready");
  }

  /*function showStep(step, stepIndex) {
    if (stepIndex && !step) {
      step = steps[stepIndex - 1];
    }

    currentStep = step;

    $(".step").hide();

    switch (currentStep.key) {
      case "step1":
        $("#step1").show();
        connection.trigger("updateButton", {
          button: "next",
          enabled: Boolean(getMessage()),
        });
        connection.trigger("updateButton", {
          button: "back",
          visible: false,
        });
        break;
      case "step2":
        $("#step2").show();
        connection.trigger("updateButton", {
          button: "back",
          visible: true,
        });
        connection.trigger("updateButton", {
          button: "next",
          text: "next",
          visible: true,
        });
        break;
      case "step3":
        $("#step3").show();
        connection.trigger("updateButton", {
          button: "back",
          visible: true,
        });
        if (lastStepEnabled) {
          connection.trigger("updateButton", {
            button: "next",
            text: "next",
            visible: true,
          });
        } else {
          connection.trigger("updateButton", {
            button: "next",
            text: "done",
            visible: true,
          });
        }
        break;
      case "step4":
        $("#step4").show();
        break;
    }
  }*/

  function save() {
    console.log("Saved called");
    // var phoneNumber = $("#phoneNumber").val();
    campaignCode = $("#campaignCode").val();

    // 'payload' is initialized on 'initActivity' above.
    // Journey Builder sends an initial payload with defaults
    // set by this activity's config.json file.  Any property
    // may be overridden as desired.
    // payload.phoneNumber = phoneNumber;
    // payload.campaignCode = campaignCode;

    payload["arguments"].execute.inArguments = [{ campaignCode: campaignCode }];

    payload["metaData"].isConfigured = true;

    connection.trigger("updateActivity", payload);
  }

  /*function getMessage() {
    return $("#select1").find("option:selected").attr("value").trim();
  }*/
});