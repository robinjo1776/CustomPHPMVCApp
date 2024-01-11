function showEmailAndPhone(rowNumber) {
  let method = $(
    "select[name='cust_phones[" + rowNumber + "][method]'] option:selected"
  ).text();
  let phoneEmail = $(
    "input[name='cust_phones[" + rowNumber + "][phone_email]']"
  );
  console.log(rowNumber);
  console.log(method);
  console.log(phoneEmail);

  if (method == "Phone") {
    phoneEmail.attr("type", "text");
    phoneEmail.attr("placeholder", "Phone Number");
    phoneEmail.parent("div.phone-email").show();
  } else if (method == "E-mail" || method == "Email") {
    phoneEmail.attr("type", "email");
    phoneEmail.attr("placeholder", "Email");
    phoneEmail.parent("div.phone-email").show();
  } else {
    phoneEmail.parent("div.phone-email").hide();
  }
}

$(document).ready(function () {
  // Initialize a counter to keep track of the rows added
  var addrRowCount = 0;
  // Initialize a counter to keep track of the rows added
  var contactRowCount = 0;

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

  $(".open-customer-btn").click(function () {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );

    $("nav").css("filter", "blur(2px)");
    $("nav").css("pointer-events", "none");

    $("table.item-list-dataTable").css("filter", "blur(2px)");
    $("table.item-list-dataTable").css("pointer-events", "none");

    $("#footer").css("filter", "blur(2px)");
    $("#footer").css("pointer-events", "none");
    if (window.location.hash == "#new-customer") {
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

    $("table.item-list-dataTable").css("filter", "blur(0px)");
    $("table.item-list-dataTable").css("pointer-events", "auto");

    $("#footer").css("filter", "blur(0px)");
    $("#footer").css("pointer-events", "auto");
    // Reset the form
    $("form.slide-form-submit")[0].reset();
    $("#customer_id").val("");
    $("#error-msg-show").html("");
    $("#address-table tbody").html("");
    $("#contact-table tbody").html("");
  });

  $(".item-list-dataTable").on("click", ".btn-edit-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    $(".open-customer-btn").trigger("click");

    // Make the AJAX GET request and read customer information
    $.ajax({
      type: "GET",
      url: API_BASE_URL + url,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response);
        $("#name").val(response.data.name);
        // $("#email").val(response.data.email);
        // $("#ctype").val(response.data.ctype);
        // $("#status_cust").val(response.data.status_cust);
        // $("#csince").val(response.data.csince);
        $("#customer_id").val(response.data.id);
        $("#savefrmBtn").text("Update");
        $(".slider-form-header").text("Update Customer");
        $("#address-table tbody").html("");
        $("#contact-table tbody").html("");

        addrRowCount = 0;
        let rowCount = 0;
        response.data.cust_addresses.forEach(function (address) {
          // Trigger the "Add more" button click
          $("#add-more-addr").trigger("click");
          rowCount++;
        });

        // Assuming data is an array of address objects
        rowCount = 0;
        response.data.cust_addresses.forEach(function (address) {
          // Get the last added row (the one we just triggered)
          var lastRow = $(
            "#address-table tbody tr.customer-addr-row-" + rowCount
          );
          // Populate the fields with data
          lastRow
            .find("select[name='cust_addresses[" + rowCount + "][cust_type]']")
            .val(address.cust_type);
          lastRow
            .find("input[name='cust_addresses[" + rowCount + "][address1]']")
            .val(address.address1);
          lastRow
            .find("input[name='cust_addresses[" + rowCount + "][address2]']")
            .val(address.address2);
          lastRow
            .find("input[name='cust_addresses[" + rowCount + "][city]']")
            .val(address.city);
          lastRow
            .find("input[name='cust_addresses[" + rowCount + "][province]']")
            .val(address.province);
          lastRow
            .find("input[name='cust_addresses[" + rowCount + "][postalCode]']")
            .val(address.postalCode);

          rowCount++;
        });

        rowCount = 0;
        contactRowCount = 0;
        response.data.cust_phones.forEach(function (address) {
          // Trigger the "Add more" button click
          $("#add-more-contact").trigger("click");
          rowCount++;
        });

        rowCount = 0;
        response.data.cust_phones.forEach(function (address) {
          // Get the last added row (the one we just triggered)
          var lastRow = $(
            "#contact-table tbody tr.customer-contact-row-" + rowCount
          );

          // Populate the fields with data
          lastRow
            .find("select[name='cust_phones[" + rowCount + "][type]']")
            .val(address.type);
          lastRow
            .find("select[name='cust_phones[" + rowCount + "][method]']")
            .val(address.method);
          lastRow
            .find("select[name='cust_phones[" + rowCount + "][method]']")
            .trigger("change");
          lastRow
            .find("input[name='cust_phones[" + rowCount + "][name]']")
            .val(address.name);
          lastRow
            .find("input[name='cust_phones[" + rowCount + "][phone_email]']")
            .val(address.phone_email);
          lastRow
            .find("input[name='cust_phones[" + rowCount + "][detailed_info]']")
            .val(address.detailed_info);

          rowCount++;
        });
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
      },
    });

    return false;
  });

  $("#slide_new_form").on("submit", function (e) {
    e.preventDefault();
    commonLib.blockUI({
      target: ".slide-form-submit",
      animate: true,
      overlayColor: "none",
    });

    if ($("#slide_new_form").valid()) {
      var formData = new FormData($("#slide_new_form")[0]);
      console.log(formData);

      url = API_BASE_URL + "customer/create";
      let customer_id = $("#customer_id").val();
      if (customer_id) {
        url = API_BASE_URL + "customer/update";
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
            // listDataTable.draw();
            location.reload();
          } else if (response.status == "name") {
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
            deleteCustomer(url);
          },
        },
      },
    });

    return false;
  });
  // Check if the current URL has a hash fragment
  if (window.location.hash == "#new-customer") {
    $(".open-customer-btn").trigger("click");
  }

  // Handle "Add more" button click
  $("#add-more-addr").on("click", function () {
    // Create a new row with input fields
    var newRow = $("<tr class='customer-addr-row-" + addrRowCount + "'>");

    var selectElement = $(
      "<select class='form-control' name='cust_addresses[" +
        addrRowCount +
        "][cust_type]'></select>"
    );
    // Add a default option
    selectElement.append("<option value=''>Select Category</option>");
    // Loop through addTypeList and add options
    let addTypeListObj = JSON.parse(addTypeList);
    for (let key in addTypeListObj) {
      if (addTypeListObj.hasOwnProperty(key)) {
        let value = addTypeListObj[key];
        selectElement.append(
          "<option value='" + key + "'>" + value + "</option>"
        );
      }
    }
    newRow.append("<td>" + selectElement.prop("outerHTML") + "</td>");

    newRow.append(
      "<td><input type='text' name='cust_addresses[" +
        addrRowCount +
        "][address1]' class='form-control' placeholder='Address1'></td>"
    );
    newRow.append(
      "<td><input type='text' name='cust_addresses[" +
        addrRowCount +
        "][address2]' class='form-control' placeholder='Address2'></td>"
    );

    // Add a delete button to the new row
    newRow.append(
      "<td><a data-addr-count=" +
        addrRowCount +
        " class='btn btn-danger delete-row'><i class='fa-sharp fa-regular fa-trash-can'></i></a></td>"
    );

    // Append the new row to the table
    $("#address-table tbody").append(newRow);

    var newRow1 = $("<tr class='customer-addr-row-" + addrRowCount + "'>");
    newRow1.append(
      "<td><input type='text' name='cust_addresses[" +
        addrRowCount +
        "][city]' class='form-control' placeholder='City'></td>"
    );
    newRow1.append(
      "<td><input type='text' name='cust_addresses[" +
        addrRowCount +
        "][province]' class='form-control' placeholder='Province'></td>"
    );
    newRow1.append(
      "<td><input type='text' name='cust_addresses[" +
        addrRowCount +
        "][postalCode]' class='form-control' placeholder='Postal Code'></td>"
    );

    newRow1.append("<td></td>");
    $("#address-table tbody").append(newRow1);

    // Increment the counter
    addrRowCount++;
  });

  // Handle delete button click
  $("#address-table").on("click", ".delete-row", function () {
    let count = $(this).data("addr-count");
    console.log(count);
    // Find the parent row and remove it
    $("tr.customer-addr-row-" + count).remove();
  });

  // Handle "Add more" button click
  $("#add-more-contact").on("click", function () {
    // Create a new row with input fields
    var newRow = $("<tr class='customer-contact-row-" + contactRowCount + "'>");
    var selectElement = $(
      "<select class='form-control' name='cust_phones[" +
        contactRowCount +
        "][type]'></select>"
    );
    // Add a default option
    selectElement.append("<option value=''>Select Category</option>");
    // Loop through addTypeList and add options
    let addTypeListObj = JSON.parse(contactTypeList);
    for (let key in addTypeListObj) {
      if (addTypeListObj.hasOwnProperty(key)) {
        let value = addTypeListObj[key];
        selectElement.append(
          "<option value='" + key + "'>" + value + "</option>"
        );
      }
    }
    newRow.append("<td>" + selectElement.prop("outerHTML") + "</td>");

    selectElement = $(
      "<select class='form-control' name='cust_phones[" +
        contactRowCount +
        "][method]' onchange='showEmailAndPhone(" +
        contactRowCount +
        ")'></select>"
    );
    // Add a default option
    selectElement.append("<option value=''>Select Method</option>");
    // Loop through addTypeList and add options
    addTypeListObj = JSON.parse(contactMethodList);
    for (let key in addTypeListObj) {
      if (addTypeListObj.hasOwnProperty(key)) {
        let value = addTypeListObj[key];
        selectElement.append(
          "<option value='" + key + "'>" + value + "</option>"
        );
      }
    }
    newRow.append("<td>" + selectElement.prop("outerHTML") + "</td>");

    newRow.append(
      "<td><div class='phone-email' style='display:none;'><input type='text' name='cust_phones[" +
        contactRowCount +
        "][phone_email]' class='form-control' placeholder='Contact Person Name'></div></td>"
    );

    // Add a delete button to the new row
    newRow.append(
      "<td><a data-contact-count=" +
        contactRowCount +
        " class='btn btn-danger delete-row'><i class='fa-sharp fa-regular fa-trash-can'></i></a></td>"
    );
    // Append the new row to the table
    $("#contact-table tbody").append(newRow);

    var newRow1 = $(
      "<tr class='customer-contact-row-" + contactRowCount + "'>"
    );
    newRow1.append(
      "<td colspan='1'><input type='text' name='cust_phones[" +
        contactRowCount +
        "][name]' class='form-control' placeholder='Contact Person Name'></td>"
    );
    newRow1.append(
      "<td colspan='2'><input type='text' name='cust_phones[" +
        contactRowCount +
        "][detailed_info]' class='form-control' placeholder='Detailed Information'></td>"
    );
    newRow1.append("<td></td>");
    $("#contact-table tbody").append(newRow1);

    // Increment the counter
    contactRowCount++;
  });

  // Handle delete button click
  $("#contact-table").on("click", ".delete-row", function () {
    let count = $(this).data("contact-count");
    console.log(count);
    // Find the parent row and remove it
    $("tr.customer-contact-row-" + count).remove();
  });

  // open import data button
  $(".open-import-data-btn")
    .off()
    .on("click", function (e) {
      $(".slide-import-form-block").animate(
        {
          width: "toggle",
        },
        400
      );

      $("nav").css("filter", "blur(2px)");
      $("nav").css("pointer-events", "none");

      $("table.item-list-dataTable").css("filter", "blur(2px)");
      $("table.item-list-dataTable").css("pointer-events", "none");

      $("#footer").css("filter", "blur(2px)");
      $("#footer").css("pointer-events", "none");
    });
  $(".close-import-frm-btn").click(function () {
    $(".slide-import-form-block").animate(
      {
        width: "toggle",
      },
      400
    );
    $("nav").css("filter", "blur(0px)");
    $("nav").css("pointer-events", "auto");

    $("table.item-list-dataTable").css("filter", "blur(0px)");
    $("table.item-list-dataTable").css("pointer-events", "auto");

    $("#footer").css("filter", "blur(0px)");
    $("#footer").css("pointer-events", "auto");
  });

  $("#slide_import_form").validate({
    errorClass: "span-error",
    errorElement: "span",
  });

  $("#slide_import_form").on("submit", function (e) {
    e.preventDefault();
    commonLib.blockUI({
      target: ".slide-import-form-submit",
      animate: true,
      overlayColor: "none",
    });

    if ($("#slide_import_form").valid()) {
      let formData = new FormData(e.target);
      // formData.append("deleteAllCus", $("#deleteAllCus:checked").val());
      // formData.append("deleteOneCus", $("#deleteOneCus:checked").val());
      // formData.append("deleteCusAddCon", $("#deleteCusAddCon:checked").val());
      // formData.append(
      //   "importFile",
      //   document.getElementById("import_file").files[0]
      // );
      console.log(formData);

      let url = API_BASE_URL + "customer/import-customer";

      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false, // Important: Don't process the data
        contentType: false, // Important: Don't set the content type
        success: function (response) {
          // Handle the successful response from the server
          console.log(response.data);
          commonLib.unblockUI(".slide-import-form-submit");
          if (response.status == true) {
            let title = "Success";
            let type = "success";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-import-frm-btn").trigger("click");
            // Add new data to the DataTable
            // listDataTable.draw();
            location.reload();
          } else {
            let title = "Error";
            let type = "error";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-import-frm-btn").trigger("click");
          }
        },
        error: function (xhr, status, error) {
          // Handle errors
          console.error(error);
          commonLib.unblockUI(".slide-import-form-submit");
        },
      });
    } else {
      commonLib.unblockUI(".slide-import-form-submit");
    }
    return false;
  });

  // When the "deleteAllCus" checkbox is clicked
  $("#deleteAllCus").change(function () {
    if ($(this).prop("checked")) {
      // If "deleteAllCus" is checked, disable "deleteOneCus" and "deleteCusAddCon"
      $("#deleteOneCus, #deleteCusAddCon").prop("disabled", true);
      $("#deleteOneCus, #deleteCusAddCon").prop("checked", false);
    } else {
      // If "deleteAllCus" is unchecked, enable "deleteOneCus" and "deleteCusAddCon"
      $("#deleteOneCus, #deleteCusAddCon").prop("disabled", false);
    }
  });

  // When the "deleteOneCus" checkbox is clicked
  $("#deleteOneCus").change(function () {
    if ($(this).prop("checked")) {
      // If "deleteOneCus" is checked, disable "deleteCusAddCon"
      $("#deleteCusAddCon").prop("disabled", true);
      $("#deleteCusAddCon").prop("checked", false);
    } else {
      // If "deleteOneCus" is unchecked, enable "deleteCusAddCon"
      $("#deleteCusAddCon").prop("disabled", false);
    }
  });
});

function deleteCustomer(url) {
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
