// Simple form confirmation
document.getElementById("bookingForm").addEventListener("submit", function (e) {
  const formMessage = document.getElementById("formMessage");
  formMessage.innerHTML = "Sending your booking request...";
});
