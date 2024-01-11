$(document).ready(function () {
  var listDataTable = $(".item-list-dataTable").DataTable({
    //disable sorting on last column
    paging: true,
    // "bLengthChange": false,
    // "bFilter": true,
    // "bInfo": false,
    // "bAutoWidth": false,
    responsive: true,
  });
  listDataTable.column(0).visible(false);

  $("#order_form").validate({
    errorClass: "span-error",
    errorElement: "span",
  });

  $(".open-user-btn").click(function () {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );

    $("nav").css("filter", "blur(2px)");
    $("nav").css("pointer-events", "none");

    $("table").css("filter", "blur(2px)");
    $("table").css("pointer-events", "none");

    $("#footer").css("filter", "blur(2px)");
    $("#footer").css("pointer-events", "none");

    if (window.location.hash == "#new-user") {
      // Remove the hash fragment without triggering a page refresh
      history.replaceState(null, document.title, window.location.pathname);
    }
  });
  $(".close-frm-btn").click(function () {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );
    $("nav").css("filter", "blur(0px)");
    $("nav").css("pointer-events", "auto");

    $("table").css("filter", "blur(0px)");
    $("table").css("pointer-events", "auto");

    $("#footer").css("filter", "blur(0px)");
    $("#footer").css("pointer-events", "auto");
    // Reset the form
    $("form.slide-form-submit")[0].reset();
    $("#user_id").val("");
    $(".user-profile-pic-block img").attr(
      "src",
      "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"
    );
    $("#error-msg-show").html("");
  });

  $(".item-list-dataTable").on("click", ".btn-edit-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    $(".open-user-btn").trigger("click");

    // Make the AJAX GET request and read user information
    $.ajax({
      type: "GET",
      url: API_BASE_URL + url,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response);
        $("#name").val(response.data.name);
        $("#sname").val(response.data.sname);
        $("#uname").val(response.data.uname);
        $("#utype").val(response.data.utype);
        $("#email").val(response.data.email);
        $("#pass").val(response.data.pass);
        $("#cpass").val(response.data.pass);
        $("#add1").val(response.data.add1);
        $("#add2").val(response.data.add2);
        $("#pcode").val(response.data.pcode);
        $("#phone").val(response.data.phone);
        $("#city").val(response.data.city);
        $("#province").val(response.data.province);
        $("#status_user").val(response.data.status_user);
        $("#user_id").val(response.data.id);
        if (response.data.pic) {
          $(".user-profile-pic-block img").attr(
            "src",
            API_BASE_URL + "uploads/users/" + response.data.pic
          );
        }

        $("#savefrmBtn").text("Update");
        $(".slider-form-header").text("Update User");
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
      },
    });

    return false;
  });

  $(".slide-form-submit").on("submit", function (e) {
    e.preventDefault();
    commonLib.blockUI({
      target: ".slide-form-submit",
      animate: true,
      overlayColor: "none",
    });

    if ($("#order_form").valid()) {
      // Create a new FormData object and append the file input field
      var formData = new FormData(this);
      // var fileInput = document.getElementById("fileToUpload");
      // formData.append("fileToUpload", fileInput.files[0]);

      url = API_BASE_URL + "user/create";
      let user_id = $("#user_id").val();
      if (user_id) {
        url = API_BASE_URL + "user/update";
      }

      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false, // Important: Don't process the data
        contentType: false, // Important: Don't set the content type
        success: function (response) {
          // Handle the successful response from the server
          console.log(response.data);
          commonLib.unblockUI(".slide-form-submit");
          if (response.status == true) {
            let title = "Success";
            let type = "success";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-frm-btn").trigger("click");
            // Add new data to the DataTable
            listDataTable.draw();
            location.reload();
          } else if (response.status == "uname") {
            let alertError = "";
            alertError +=
              '<div class="alert alert-danger alert-dismissible" role="alert">';
            alertError +=
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            alertError += '<span aria-hidden="true">&times;</span>';
            alertError += "</button>";
            alertError += response.mmsg;
            alertError += "</div>";
            $("#error-msg-show").html(alertError);
          } else {
            let title = "Error";
            let type = "error";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-frm-btn").trigger("click");
          }
        },
        error: function (xhr, status, error) {
          // Handle errors
          console.error(error);
          commonLib.unblockUI(".slide-form-submit");
        },
      });
    } else {
      commonLib.unblockUI(".slide-form-submit");
    }

    return false;
  });

  $(".item-list-dataTable").on("click", "a.btn-delete-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");

    let deleteCnf = $.confirm({
      title: "Delete Item!",
      content: "Are you sure you want to delete this item?",
      type: "red",
      typeAnimated: true,
      columnClass:
        "col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
      buttons: {
        close: {
          text: "NO",
          function() {},
        },
        ok: {
          text: "YES",
          btnClass: "btn-red",
          action: function () {
            deleteCnf.close();
            deleteLoad(url);
          },
        },
      },
    });

    return false;
  });

  // Check if the current URL has a hash fragment
  if (window.location.hash == "#new-user") {
    $(".open-user-btn").trigger("click");
  }
});

function deleteLoad(url) {
  commonLib.blockUI({
    target: ".dt-list-item-block",
    animate: true,
    overlayColor: "none",
  });

  $.ajax({
    type: "GET",
    url: API_BASE_URL + url,
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      // Handle the successful response from the server
      console.log(response.data);
      commonLib.unblockUI(".dt-list-item-block");
      let title = response.status ? "Success" : "Error";
      let type = response.status ? "success" : "error";
      commonLib.iniToastrNotification(type, title, response.mmsg);

      listDataTable.draw();
      location.reload();
    },
    error: function (xhr, status, error) {
      // Handle errors
      console.error(error);
      commonLib.unblockUI(".dt-list-item-block");
    },
  });
}
