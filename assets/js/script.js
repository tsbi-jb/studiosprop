// 1. Inject quote_id into the form as a hidden field
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("proposalForm");

  const quoteId = `Q-${new Date().toISOString().slice(2, 10).replace(/-/g, '')}-${Math.floor(Math.random() * 90 + 10)}`;
  const hiddenQuoteField = document.createElement("input");
  hiddenQuoteField.type = "hidden";
  hiddenQuoteField.name = "quote_id";
  hiddenQuoteField.value = quoteId;

  form.appendChild(hiddenQuoteField);
});

// 2. Load services from backend and inject checkboxes
fetch("get_services.php")
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById("service-list");

    data.forEach(service => {
      const div = document.createElement("div");
      div.classList.add("form-check");

      div.innerHTML = `
        <input class="form-check-input service-checkbox" 
               type="checkbox" 
               name="services[]" 
               value="${service.id}" 
               data-rate="${service.rate_per_day}" 
               id="service-${service.id}">

        <label class="form-check-label" for="service-${service.id}">
          ${service.service_name}
        </label>
      `;

      container.appendChild(div);
    });

    attachLiveCalculation();
  })
  .catch(err => {
    console.error("Error loading services:", err);
    document.getElementById("service-list").innerHTML =
      "<p class='text-danger'>Error loading services.</p>";
  });

// 3. Live cost calculation logic
function attachLiveCalculation() {
  const daysInput = document.getElementById("days");
  const totalDisplay = document.getElementById("totalDisplay");

  function calculateTotal() {
    const days = parseInt(daysInput.value) || 0;
    let total = 0;

    document.querySelectorAll(".service-checkbox:checked").forEach(cb => {
      const rate = parseInt(cb.dataset.rate);
      total += rate * days;
    });

    totalDisplay.textContent = `₹${total}`;
  }

  daysInput.addEventListener("input", calculateTotal);
  document.addEventListener("change", calculateTotal);
}