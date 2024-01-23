<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
  <style>
    #preview container py-4 {
      width: 70%;
  height: 100vh;
  border: 1px solid #ccc;
  padding: 10px;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 10px;
  margin: 20px auto;
  overflow-y: scroll;
  padding-left: 547px;
}

h3{
  float: left;
}
    .all-form{
    display:flex;
    margin-left:20px;
    margin-right:20px;
    }
    
    @media only screen and (max-width:640px){
     .all-form{
    display:flex;
    flex-wrap:wrap;
    }
    
    #htmlPreview{
        width:100hw;
    height:100vh;
    }
    }
    
    #htmlPreview{
    width:60hw;
    height:100vh;
    position: relative;
    }
  </style>
</head>
<body>

<div class="all-form">
<!-- Wrapper container -->
<div class="container py-4 frame">
  <!-- Bootstrap 5 starter form -->
  <form id="contactForm" method="post" action="bulksender.php" enctype="multipart/form-data">
  
    <!-- Login -->
    <div class="mb-3">
      <label class="form-label" for="emailAddress">Login First</label>
      <input class="form-control" id="emailAddress" type="email" name="u-name" placeholder="Enter your Login Email" required="">
      <input class="form-control" id="emailPassword" type="password" name="pass" placeholder="Enter your Login Password" required="">
      <label class="form-label" for="senderEmail">Sender Details</label>
      <input class="form-control" id="senderEmail" type="email" name="s-email" placeholder="Sender Email" required="">
      <input class="form-control" id="senderName" type="text" name="s-name" placeholder="Sender Name" required="">
    </div>

    <!-- Name input -->
    <div class="mb-3">
      <label class="form-label" for="emails">Enter Emails  !Important** For Bulk Sending, use comma "," after each email</label>
      <input class="form-control" id="emails" type="text" name="emails" placeholder="Enter your Email" required="">
    </div>

    <!-- Email address input -->
    <div class="mb-3">
      <label class="form-label" for="subject">Subject</label>
      <input class="form-control" id="subject" type="text" name="subject" placeholder="Enter your Subject" required="">
    </div>

    <!-- Message input -->
    <div class="mb-3">
      <label class="form-label" for="message">Message/Customize HTML</label>
      <textarea id="message" class="form-control" name="message" placeholder="Enter your Message or Drop your File Here." style="height: 10rem;" required=""></textarea>
    </div>
    
    

    <!-- Form submit button -->
    <div class="d-grid">
      <button class="btn btn-primary btn-lg" type="submit" name="send" value="Send" onclick="validateForm()">Send</button>
    </div>
  </form>
</div>

<div class="right-frame">
<!-- HTML Preview Container -->
<div id="previewContainer" class="container py-4">
      <h3>HTML Preview</h3>
      <iframe id="htmlPreview" class="preview container py-4" frameborder="0" style="position:relative;border: 1px solid #b1b1b1;"></iframe>
    </div>
</div>
</div>

<script>
  var textarea = document.getElementById("message");
  var htmlPreview = document.getElementById("htmlPreview");

  // Update HTML preview on textarea input
  textarea.addEventListener("input", updatePreview);

  // Handle the dropped file
  function handleDrop(event) {
    event.preventDefault();

    // Get the dropped file
    var file = event.dataTransfer.files[0];

    // Check if the file is an HTML file
    if (file.type === "text/html") {
      // Read the file content
      var reader = new FileReader();
      reader.onload = function(e) {
        var content = e.target.result;
        textarea.value = content;
        updatePreview();
      };
      reader.readAsText(file);
    } else {
      textarea.value = "Please drop an HTML file.";
      htmlPreview.srcdoc = "";
    }
  }

  // Update HTML preview
  function updatePreview() {
    var content = textarea.value;
    htmlPreview.srcdoc = content;
  }

  // Validate the form before submission
  function validateForm() {
    var email = document.getElementById("emailAddress").value;
    var password = document.getElementById("emailPassword").value;
    var senderEmail = document.getElementById("senderEmail").value;
    var senderName = document.getElementById("senderName").value;
    var emails = document.getElementById("emails").value;
    var subject = document.getElementById("subject").value;
    var message = document.getElementById("message").value;

    if (!email || !password || !senderEmail || !senderName || !emails || !subject || !message) {
      alert("Please fill in all the required fields.");
      return false;
    }
  }

  // Prevent default behavior for drag events
  document.addEventListener("dragenter", preventDefault, false);
  document.addEventListener("dragover", preventDefault, false);
  document.addEventListener("dragleave", preventDefault, false);
  document.addEventListener("drop", preventDefault, false);

  // Prevent default behavior
  function preventDefault(event) {
    event.preventDefault();
  }

  // Handle the drop event
  textarea.addEventListener("drop", handleDrop, false);
</script>

</body>
</html>