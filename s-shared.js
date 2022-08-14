function ShowLoader(status) {
  var spinner = document.getElementById("cover-spin");
  if (status) {
    spinner.style.display = "block";
  } else {
    spinner.style.display = "none";
  }
}
