
    document.getElementById("healthForm").addEventListener("submit", function(event) {
      event.preventDefault();

      const formData = new FormData();
      formData.append("name", document.getElementById("name").value);
      formData.append("age", document.getElementById("age").value);
      formData.append("weight", document.getElementById("weight").value);
      formData.append("email", document.getElementById("email").value);
      formData.append("healthReport", document.getElementById("healthReport").files[0]);

      fetch("insert.php", {
        method: "POST",
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert("Data inserted successfully!");
          document.getElementById("healthForm").reset();
        } else {
          alert("Error occurred while inserting data.");
        }
      })
      .catch(error => {
        alert("Error occurred while processing the form.");
      });
    });
