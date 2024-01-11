document.getElementById("printButton").addEventListener("click", function () {
  // Show the table for printing
  var bolBlock = document.getElementById("bolBlock");
  var saveAsPdfButton = document.getElementById("saveAsPdfButton");
  this.style.display = "none";
  bolBlock.style.marginTop = "0";
  saveAsPdfButton.style.display = "none";

  // Trigger the print dialog
  window.print();

  // Hide the table after printing (optional)
  this.style.display = "unset";
  bolBlock.style.marginTop = "40px";
  saveAsPdfButton.style.display = "unset";
});

$(document).ready(function () {});
