$(document).ready(function () {
  var subCategoryId = "";

  var listDataTable = $(".item-list-dataTable").DataTable({
    //disable sorting on last column
    paging: true,
    responsive: true,
  });
  listDataTable.column(0).visible(false);

  $("#slide_new_form").validate({
    errorClass: "span-error",
    errorElement: "span",
  });

  $(".open-pro-btn").click(function () {
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

    if (window.location.hash == "#new-product") {
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
    $("#pro_id").val("");
    $("#error-msg-show").html("");
  });

  $(".item-list-dataTable").on("click", ".btn-edit-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    console.log(url);
    $(".open-pro-btn").trigger("click");

    // Make the AJAX GET request and read product information
    $.ajax({
      type: "GET",
      url: API_BASE_URL + url,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response);
        $("#code").val(response.data.code);
        $("#cat_id").val(response.data.cat_id);
        subCategoryId = response.data.scat_id;
        $("#cat_id").trigger("change");
        $("#price").val(response.data.price);
        $("#description").val(response.data.description);
        $("#pd").val(response.data.pd);
        $("#bbd").val(response.data.bbd);
        $("#minw").val(response.data.minw);
        $("#maxw").val(response.data.maxw);
        $("#unit").val(response.data.unit);
        $("#upc").val(response.data.upc);
        $("#item_in_box").val(response.data.item_in_box);
        $("#comments").val(response.data.comments);
        $("#size_des").val(response.data.size_des);
        $("#pro_id").val(response.data.id);
        $("#status_prod").val(response.data.status_prod);
        $("#savefrmBtn").text("Update");
        $(".slider-form-header").text("Update Product");
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
    if ($("#slide_new_form").valid()) {
      var formData = new FormData(this);

      url = API_BASE_URL + "product/create";
      let pro_id = $("#pro_id").val();
      if (pro_id) {
        url = API_BASE_URL + "product/update";
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
            location.reload();
          } else if (response.status == "code") {
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
            deleteProduct(url);
          },
        },
      },
    });

    return false;
  });

  // Check if the current URL has a hash fragment
  if (window.location.hash == "#new-product") {
    $(".open-pro-btn").trigger("click");
  }

  $("#cat_id").on("change", function (e) {
    let cat_id = $(this).val();
    commonLib.blockUI({
      target: ".slide-form-submit",
      animate: true,
      overlayColor: "none",
    });

    // Make the AJAX GET request and read order information
    let formData = {
      cat_id: cat_id,
    };
    $.ajax({
      type: "POST",
      url: API_BASE_URL + "product-sub-category/get-category",
      data: JSON.stringify(formData),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        commonLib.unblockUI(".slide-form-submit");
        subCategoryList = response.data;

        // Clear existing options
        $("#scat_id").empty();
        // add empty options
        $("#scat_id").append(
          $("<option>", {
            value: "",
            text: "Select Sub Category",
          })
        );
        // Loop through the response and create options
        $.each(response.data, function (index, category) {
          $("#scat_id").append(
            $("<option>", {
              value: category.id,
              text: category.scat_name,
            })
          );
        });
        $("#scat_id").val(subCategoryId);
        // $("#scat_id").trigger("change");
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        commonLib.unblockUI(".slide-form-submit");
      },
    });
  });
});

function deleteProduct(url) {
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
    },
    error: function (xhr, status, error) {
      // Handle errors
      console.error(error);
      commonLib.unblockUI(".dt-list-item-block");
    },
  });
}
