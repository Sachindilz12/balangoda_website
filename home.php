<?php
session_start();
require 'database.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $sname = $_POST['sname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO messages (first_name, second_name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fname, $sname, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message sent successfully!";
    } else {
        $_SESSION['error_message'] = "Error sending message. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="source/css/main.css">
    <link rel="stylesheet" href="source/css/home.css">
    <title>Home - Balangoda Website</title>

<style>
    .gallery {
    width: 100%;
    margin-top:20px;
    height: 60vmin;
    display: flex;
    gap: 5px;
}

/* Gallery Images */
.gallery img {
    height: 100%;
    overflow: hidden;
    flex: 1;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition:all 0.3s ;
}

/* Hover Effect */
.gallery img:hover {
    flex:4;
}

</style>
    <header>
    <div class="logo-container">
        <img src="source/Images/logo.png" alt="Balangoda Municipal Council Logo" class="logo">
        <h1><span class="balangoda">Balangoda</span><br>Municipal Council</h1>

    </div>
    <div class="line"></div>
        <nav>
        <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>  
        </nav>
    </header>
</head>
<body>
<div class="gallery">
    <img src="source/Images/image1.jpeg" alt="Image 1">
    <img src="source/Images/image2.jpeg" alt="Image 2">
    <img src="source/Images/image3.jpeg" alt="Image 3">
    <img src="source/Images/image4.jpeg" alt="Image 4">
    <img src="source/Images/image5.jpeg" alt="Image 5">

</div>
 
    <main>



        <div class="content">
    <h2> Welcome to the official website of Balangoda Municipal Council.</h2>
    <p>
   This is the oldest municipal council located in Ratnapura district of Sabaragamuwa province. Mr. Barnes Ratwatta Disawe, the father of the world's first Prime Minister, Mrs. Sirimavo Dias Bandaranaike, was the first chairman of this municipal council, which was established by Governor Sir Henry Brownie in 1939. This municipal council, which started as the Senatorial Board, has been controlled by 12 mayors and a special commissioner for 9 years. Today, the permanent resident population exceeds 40,000. Balangoda Municipal Council is an institution that provides basic facilities to about 77,026 people who use the city on a daily basis with an area of ​​16.2 square kilometers. As of today, there are 104 permanent sanctioned staff and 37 substitute staff.
    <br>
    Best performing Municipal Council in 2005:
        In the year 2005, the Balangoda Municipal Council won the first place in the island in the local government category in the National Productivity Competition organized by the National Productivity Secretariat. Necessary documents are noted on the boards of the Municipal Council office and the services are provided to the people very quickly. Also, printed community response forms and the electronic community response system, which is used for the first time in Sri Lanka, will also be used to get people's opinions. The Balangoda Municipal Council has been able to respond within 48 hours through the electronic community response system. An opportunity to get the applications, information, etc. to be obtained from the Municipal Council is provided through this official website. Through the official blog of the Municipal Council, we have provided an opportunity to inform the people about the activities carried out by the Municipal Council very quickly and to comment on them. Also, through social networking websites like Facebook, Twitter, Youtube etc., the people have been given the opportunity to communicate directly with our municipal council and get information. Balangoda Municipal Council is the first municipality in Sri Lanka to connect with people through social networking sites.
    </p>
    <div id="map" style="height: 500px; width: 100%; margin-top: 20px;"></div>
<button onclick="getLocation()" style="display: block; margin: 20px auto;background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Get Location</button>
<div id="location" style="text-align: center; font-weight: bold;"></div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-ykqvBfiFzqHZThbJSEJz-qDWaB3PRu4"></script>
<script>
    var map;
    var infoWindow;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 6.647770304059926, lng: 80.70128117190956 }, 
            zoom: 16
        });
        infoWindow = new google.maps.InfoWindow;
    }
    function getLocation() {
        
        var fixedLocation = {
            lat: 6.647770304059926,
            lng: 80.70128117190956
        };

        infoWindow.setPosition(fixedLocation);
        infoWindow.setContent('Balangoda Town');
        infoWindow.open(map);
        map.setCenter(fixedLocation);
        document.getElementById("location").innerHTML = "Location Updated";
    }

    window.onload = initMap;
</script>

</div>
</main>
       

        <div class="about">
            <h1>Contact Us</h1>
        </div>  
        <div style="display: flex; justify-content: center;">
            
        <div class="contact-main">
                <div class="contact-left">
                    <h2 style="font-size: 30px;">How can we help you?</h2>
                    <form action="" method="post">
                        <input class="input" type="text" name="fname" placeholder="First Name" required> <br> <br>
                        <input class="input" type="text" name="sname" placeholder="Second Name" required> <br> <br>
                        <input class="input" type="email" name="email" placeholder="Your Email" required> <br> <br>
                        <input class="input" type="text" name="subject" placeholder="Subject" required> <br> <br>
                        <textarea class="input" name="message" rows="4" cols="40" placeholder="Your Message" required></textarea> <br> <br>
                        <input class="aboutbutton1" type="submit" name="submit" value="Send Message">
                    </form>
                </div>
                <div class="contact-right">
                    <h2>Email</h2>
                    <p>For general inquiries and assistance, please email us at: <a href="mailto:info@balangoda.com">info@balangoda.com</a></p>

                    <h2>Phone</h2>
                    <p>You can reach our support team at <a href="tel:+94711234567">+94 45 223 4067</a></p>

                    <h2>Physical Address</h2>
                    <p>Municipal Counsil<br>Main Street<br>Bans Rathwaththa Road, Balangoda. 70100</p>
                </div>
            </div>
        </div>
    

     


    <div class="footer">
        <div class="footer1">
            <p>E-Balangoda - 2024. All rights reserved. The content, images, and materials on this website are protected by copyright law and may not be used, reproduced, or distributed without permission.</p>
        </div>
    </div>





</body>
</html>
