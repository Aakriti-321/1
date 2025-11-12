const popup = document.getElementById("popup-bg");

function hideshow() {
 
  if (popup.style.display === "flex") {
    popup.style.display = "none";
  } else {
    popup.style.display = "flex";
  }
}

function confirmBooking() {
  
  const pickup = document.getElementById("pickup").value.trim();
  const drop = document.getElementById("drop").value.trim();
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;

  if (pickup === "" || drop === "" || startDate === "" || endDate === "") {
    alert(" Please fill in all fields before confirming your booking.");
    return; 
  }


  alert("✔ Booking Confirmed!\nPickup: " + pickup + "\nDrop: " + drop);
  popup.style.display = "none";
}
