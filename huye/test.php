<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auto-Loading Modal</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f4f4f9;
    }
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s, visibility 0.3s;
    }
    .modal {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }
    .modal h2 {
      margin: 0 0 10px;
    }
    .modal p {
      margin: 0 0 20px;
      font-size: 16px;
    }
    .modal button {
      padding: 10px 20px;
      font-size: 16px;
      color: #fff;
      background: #007bff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .modal button:hover {
      background: #0056b3;
    }
    .modal-overlay.active {
      visibility: visible;
      opacity: 1;
    }
  </style>
</head>
<body>

<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <h2>Quick Notification</h2>
    <p>This is an auto-loaded modal message.</p>
    <button id="closeButton">OK</button>
  </div>
</div>

<script>
  // Automatically show the modal when the page loads
  document.addEventListener('DOMContentLoaded', () => {
    const modalOverlay = document.getElementById('modalOverlay');
    const closeButton = document.getElementById('closeButton');

    // Show modal
    modalOverlay.classList.add('active');

    // Close modal on button click
    closeButton.addEventListener('click', () => {
      modalOverlay.classList.remove('active');
    });
  });
</script>

</body>
</html>
