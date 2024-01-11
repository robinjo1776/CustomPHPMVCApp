var oauth;

$(document).ready(function () {
  var invoices = [];
  var orderDataTable = $(".item-list-dataTable").DataTable({
    paging: true,
    responsive: true,
    columnDefs: [{ orderable: false, targets: 0 }],
  });

  // Event listener for "select all" checkbox
  $(".select-invoice-ids-all").change(function () {
    var allCheckboxes = $(".item-list-dataTable").find(".select-invoice-ids");
    if (this.checked) {
      if (invoices.length > 9) {
        alert(
          "You can't have more than 10 invoices imported at once in QuickBooks."
        );
        return false;
      }

      // Check all checkboxes
      allCheckboxes.prop("checked", true);

      // Since all checkboxes are now checked, you might want to add all invoiceIds to your 'invoices' array here
      allCheckboxes.each(function () {
        var invoiceId = $(this).val();
        if ($.inArray(invoiceId, invoices) === -1) {
          invoices.push(invoiceId);
        }
      });
    } else {
      // Uncheck all checkboxes
      allCheckboxes.prop("checked", false);

      // Since all checkboxes are now unchecked, you might want to clear your 'invoices' array here
      invoices = [];
    }

    commonLib.blockUI({
      target: ".order-list-block",
      animate: true,
      overlayColor: "none",
    });

    if (invoices.length) {
      $("#invoice-btn-container").show();
    } else {
      $("#invoice-btn-container").hide();
    }
    // Target the div where you want to insert the checkboxes
    var $invoiceListDiv = $("#invoice-id-list");
    // Clear the current list
    $invoiceListDiv.empty();

    // Create checkboxes for each invoice in the array
    $.each(invoices, function (index, value) {
      var parts = value.split("##");
      var checkboxValue = parts[0];
      var checkboxLabel = parts[1];

      // Create checkbox and label elements
      var $checkbox = $("<input />", {
        onclick: "return false;",
        onkeydown: "return false;",
        name: "invoicenos[]",
        type: "checkbox",
        id: "invoice_" + checkboxValue,
        value: checkboxValue,
        checked: "checked", // Since these represent the invoices array, they should start as checked
      }).click(function () {
        //handleCheckboxChange($(this));
      });

      var $label = $("<label />", {
        for: "invoice_" + checkboxValue,
        text: checkboxLabel,
      });
      var $checkboxDiv = $("<div />", {
        class: "invoice-checkbox-container",
      });

      $checkboxDiv.append($checkbox).append($label);
      // Append to the div
      $invoiceListDiv.append($checkboxDiv);
    });

    commonLib.unblockUI(".order-list-block");
  });

  // $(".select-invoice-ids").on("click", function (e) {
  $(".item-list-dataTable").on("click", ".select-invoice-ids", function (e) {
    var invoiceId = $(this).val();
    if ($(this).is(":checked")) {
      if (invoices.length > 9) {
        alert(
          "You can't have more than 10 invoices imported at once in QuickBooks."
        );
        return false;
      }

      // If checked, add the record to the array if it's not already there
      if ($.inArray(invoiceId, invoices) === -1) {
        invoices.push(invoiceId);
      }
    } else {
      // If unchecked, remove the record from the array
      invoices = $.grep(invoices, function (value) {
        return value !== invoiceId;
      });
    }
    // Output the current state of the invoices array to console (for debugging)
    console.log(invoices);
    if (invoices && invoices.length > 0) {
      $("#invoice-btn-container").show();
    } else {
      $("#invoice-btn-container").hide();
    }

    commonLib.blockUI({
      target: ".order-list-block",
      animate: true,
      overlayColor: "none",
    });
    // Target the div where you want to insert the checkboxes
    var $invoiceListDiv = $("#invoice-id-list");
    // Clear the current list
    $invoiceListDiv.empty();

    // Create checkboxes for each invoice in the array
    $.each(invoices, function (index, value) {
      var parts = value.split("##");
      var checkboxValue = parts[0];
      var checkboxLabel = parts[1];

      // Create checkbox and label elements
      var $checkbox = $("<input />", {
        onclick: "return false;",
        onkeydown: "return false;",
        name: "invoicenos[]",
        type: "checkbox",
        id: "invoice_" + checkboxValue,
        value: checkboxValue,
        checked: "checked", // Since these represent the invoices array, they should start as checked
      }).click(function () {
        //handleCheckboxChange($(this));
      });

      var $label = $("<label />", {
        for: "invoice_" + checkboxValue,
        text: checkboxLabel,
      });
      var $checkboxDiv = $("<div />", {
        class: "invoice-checkbox-container",
      });

      $checkboxDiv.append($checkbox).append($label);
      // Append to the div
      $invoiceListDiv.append($checkboxDiv);
    });
    commonLib.unblockUI(".order-list-block");
  });

  // quickbook download
  var OAuthCode = function (url) {
    this.loginPopup = function () {
      this.loginPopupUri();
    };

    this.loginPopupUri = function () {
      // Launch Popup
      var parameters = "location=1,width=800,height=650";
      parameters +=
        ",left=" +
        (screen.width - 800) / 2 +
        ",top=" +
        (screen.height - 650) / 2;

      var win = window.open(url, "connectPopup", parameters);
      var pollOAuth = window.setInterval(function () {
        try {
          if (win.document.URL.indexOf("code") != -1) {
            window.clearInterval(pollOAuth);
            win.close();
            location.reload();
          }
        } catch (e) {
          console.log(e);
        }
      }, 100);
    };
  };

  oauth = new OAuthCode(qb_auth_url);

  /*$("#qb_aacount").select2();
  $("#qb_aacount").on("change", function (e) {
    let val = $(e.target).val();
    console.log("Selected Value: ", val);
    $("#qb_account_id").val(val);
  });*/
});

function qbPopup() {
  oauth.loginPopup();
}
