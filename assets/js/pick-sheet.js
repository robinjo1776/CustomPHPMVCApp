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

$(document).ready(function () {});
