let tasks = [];
const taskListEl = document.getElementById("taskList");
const calendarEl = document.getElementById("calendar");

let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Select date
function selectDate(dateString) {
  document.getElementById("taskDate").value = dateString;
  document.querySelectorAll(".date").forEach(d => d.classList.remove("selected"));
  const selected = document.querySelector(`.date[data-date="${dateString}"]`);
  if (selected) selected.classList.add("selected");
  document.getElementById("taskText").focus();
}

// ======================= SERVER CONNECTION =======================
async function loadTasks() {
  try {
    const response = await fetch("task_process.php?action=fetch");
    const data = await response.json();
    tasks = Array.isArray(data) ? data : [];
    displayTasks();
    updateCalendar();
  } catch (err) {
    console.error("Error loading tasks:", err);
  }
}

async function addTask() {
  const text = document.getElementById("taskText").value;
  const date = document.getElementById("taskDate").value;
  const csrf = document.querySelector('input[name="csrf_token"]')?.value;

  if (!text && !date) return showError("Please enter a task and choose a date.");
  if (!text) return showError("Task description is required.");
  if (!date) return showError("Please select a date for your task.");

  const formData = new FormData();
  formData.append("action", "add");
  formData.append("title", text);
  formData.append("due_date", date);
  if (csrf) formData.append("csrf_token", csrf);

  try {
    const response = await fetch("task_process.php", {
      method: "POST",
      body: formData,
    });
    const result = await response.json();

    if (result.status === "success") {
      document.getElementById("taskText").value = "";
      loadTasks();
    } else {
      showError(result.message || "Failed to add task.");
    }
  } catch (err) {
    console.error("Error adding task:", err);
    showError("Connection error. Could not add task.");
  }
}

// ======================= UI FUNCTIONS =======================
function renderCalendar() {
  calendarEl.innerHTML = "";
  document.getElementById("monthYear").textContent =
    new Date(currentYear, currentMonth)
    .toLocaleString("default", { month: "long", year: "numeric" });

  const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  days.forEach(d => {
    const div = document.createElement("div");
    div.className = "day";
    div.textContent = d;
    calendarEl.appendChild(div);
  });

  const firstDay = new Date(currentYear, currentMonth, 1).getDay();
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

  for (let i = 0; i < firstDay; i++) calendarEl.appendChild(document.createElement("div"));

  for (let d = 1; d <= daysInMonth; d++) {
    const div = document.createElement("div");
    const fullDate = `${currentYear}-${String(currentMonth+1).padStart(2,"0")}-${String(d).padStart(2,"0")}`;
    div.className = "date";
    div.dataset.date = fullDate;
    div.textContent = d;
    div.addEventListener("click", () => selectDate(fullDate));
    calendarEl.appendChild(div);
  }

  updateCalendar();
}

function prevMonth() { currentMonth--; if (currentMonth < 0) { currentMonth = 11; currentYear--; } renderCalendar(); }
function nextMonth() { currentMonth++; if (currentMonth > 11) { currentMonth = 0; currentYear++; } renderCalendar(); }

function showError(message) {
  const errorMessageEl = document.getElementById("errorMessage");
  if (errorMessageEl) {
    errorMessageEl.textContent = message;
    let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  } else {
    alert(message);
  }
}

function displayTasks() {
  if (!taskListEl) return;
  taskListEl.innerHTML = "";
  if (tasks.length === 0) {
    taskListEl.innerHTML = "<p>No tasks yet.</p>";
    return;
  }
  tasks.forEach(t => {
    const div = document.createElement("div");
    div.className = "task-item";
    div.textContent = `${t.title} (${t.due_date})`;
    taskListEl.appendChild(div);
  });
}

function updateCalendar() {
  document.querySelectorAll(".date").forEach(el => {
    el.classList.remove("has-task");
    const dot = el.querySelector(".task-dot");
    if (dot) dot.remove();

    if (tasks.some(t => t.due_date === el.dataset.date)) {
      el.classList.add("has-task");
      const taskDot = document.createElement("div");
      taskDot.className = "task-dot";
      el.appendChild(taskDot);
    }
  });
}

// ======================= INIT =======================
renderCalendar();
loadTasks();
