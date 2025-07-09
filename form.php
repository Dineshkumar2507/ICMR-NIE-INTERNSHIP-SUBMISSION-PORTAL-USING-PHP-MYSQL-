<?php

$dsn = "mysql:host=localhost;dbname=internship_db;charset=utf8mb4";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $uploadDir = 'C:/God Of War/htdocs/internship/uploads/';
    $relativeDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $cvFilePath = '';
    $bonafideCertificatePath = '';

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $cvOriginalName = basename($_FILES['cv']['name']);
        $cvSafeName = uniqid('cv_') . '_' . $cvOriginalName;
        $cvFullPath = $uploadDir . $cvSafeName;
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $cvFullPath)) {
            $cvFilePath = $relativeDir . $cvSafeName;
        }
    }

    if (isset($_FILES['bonafide']) && $_FILES['bonafide']['error'] === UPLOAD_ERR_OK) {
        $bonafideOriginalName = basename($_FILES['bonafide']['name']);
        $bonafideSafeName = uniqid('bonafide_') . '_' . $bonafideOriginalName;
        $bonafideFullPath = $uploadDir . $bonafideSafeName;
        if (move_uploaded_file($_FILES['bonafide']['tmp_name'], $bonafideFullPath)) {
            $bonafideCertificatePath = $relativeDir . $bonafideSafeName;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO applications (
            email, name, age, gender, contact_no,
            emergency_contact_person, emergency_contact_no,
            education, previous_course, institution,
            institution_address, supervisor_name, expectations,
            interested_topic, cv_file_path, internship_start_date,
            internship_end_date, area_internship, preferred_mentor,
            bonafide_certificate_path, terms_accepted
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $values = [
        $_POST['email'],
        $_POST['name'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['number'],
        $_POST['emername'],
        $_POST['emernumber'],
        $_POST['education'],
        $_POST['courseeducation'],
        $_POST['institution'],
        $_POST['institutionaddress'],
        $_POST['supervisor'],
        $_POST['expectations'],
        $_POST['topic'],
        $cvFilePath,
        $_POST['startdate'],
        $_POST['enddate'],
        $_POST['area'],
        $_POST['mentor'],
        $bonafideCertificatePath,
        isset($_POST['checkbox']) ? 1 : 0
    ];

    $stmt->execute($values);

    require 'mail.php';

    sendVerificationEmail($_POST['email'], $_POST['name']);

    header("Location: form.html");
    exit();


    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
