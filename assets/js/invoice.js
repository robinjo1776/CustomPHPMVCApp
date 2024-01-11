document.getElementById("printButton").addEventListener("click", function () {
  // Show the table for printing
  var printBtnBlock = document.getElementById("printBtnBlock");
  var saveAsPdfButton = document.getElementById("saveAsPdfButton");
  this.style.display = "none";
  printBtnBlock.style.marginTop = "0";
  saveAsPdfButton.style.display = "none";

  // Trigger the print dialog
  window.print();

  // Hide the table after printing (optional)
  this.style.display = "unset";
  printBtnBlock.style.marginTop = "90px";
  printBtnBlock.style.textAlign = "right";
  saveAsPdfButton.style.display = "unset";
});

$(document).ready(function () {
  $("#optionalLabel").on("change", function (e) {
    commonLib.blockUI({
      target: "#invoice-sum-block",
      animate: true,
      overlayColor: "none",
    });

    let orderId = $("#optionalOrderId").val();
    let label = $(e.target).val();
    let url = "order/invoice-update/" + orderId;

    $.ajax({
      type: "GET",
      url: API_BASE_URL + url + "?label=" + label,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response.data);
        commonLib.unblockUI("#invoice-sum-block");
        let title = response.status ? "Success" : "Error";
        let type = response.status ? "success" : "error";
        commonLib.iniToastrNotification(type, title, response.mmsg);
        location.reload();
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        commonLib.unblockUI("#invoice-sum-block");
      },
    });
  });

  $("#optionalValue").on("change", function (e) {
    commonLib.blockUI({
      target: "#invoice-sum-block",
      animate: true,
      overlayColor: "none",
    });

    let orderId = $("#optionalOrderId").val();
    let val = $(e.target).val();
    let url = "order/invoice-update/" + orderId;

    $.ajax({
      type: "GET",
      url: API_BASE_URL + url + "?val=" + val,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response.data);
        commonLib.unblockUI("#invoice-sum-block");
        let title = response.status ? "Success" : "Error";
        let type = response.status ? "success" : "error";
        commonLib.iniToastrNotification(type, title, response.mmsg);
        location.reload();
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        commonLib.unblockUI("#invoice-sum-block");
      },
    });
  });
});
