<?php
session_start();

// Njia ya kwenda kwenye folda inayohifadhi nyaraka kutoka ndani ya folda ya ict
$docs_folder = "../assets/docs/";

// 1. Kinga ya mkdir: Jaribu kutengeneza folda kwa utulivu bila kusimamisha mfumo (kama mazingira yataruhusu)
if (!is_dir($docs_folder)) {
    @mkdir($docs_folder, 0777, true);
}

// 2. Kinga ya scandir: Hakikisha folda ipo na inasomeka ndipo utafute mafaili
$files = array();
if (is_dir($docs_folder)) {
    $scanned = scandir($docs_folder);
    if ($scanned !== false) {
        $files = array_diff($scanned, array('.', '..'));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloads - ICT Services</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; color: #333; }
        .navbar { background-color: #0d1b2a; color: #ffffff; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 22px; color: #00b4d8; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); }
        h2 { color: #0d1b2a; border-bottom: 2px solid #00b4d8; padding-bottom: 10px; margin-top: 0; }
        ul { padding-left: 20px; line-height: 2; }
        li a { color: #00b4d8; text-decoration: none; font-weight: 600; font-size: 15px; }
        li a:hover { text-decoration: underline; color: #03045e; }
        .no-files { color: #721c24; background-color: #f8d7da; padding: 12px; border-radius: 4px; font-size: 14px; }
        .back-btn { display: inline-block; margin-top: 20px; color: #0d1b2a; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>My Campus - ICT Services</h1>
        <div><a href="../index.php" style="color: white; text-decoration: none; font-weight: 600;">← Back to Home</a></div>
    </div>
    <div class="container">
        <h2>Downloads Center</h2>
        <p>Download official university documents, application forms, academic calendars, and software guides available in the system:</p>
        
        <ul>
            <?php
            if (!empty($files)) {
                foreach ($files as $file) {
                    // Epuka kusoma mafaili yasiyotakiwa ya mfumo
                    if ($file == '.' || $file == '..') continue;
                    
                    // Safisha jina la faili ili lionekane vizuri
                    $file_display_name = pathinfo($file, PATHINFO_FILENAME);
                    $file_display_name = ucwords(str_replace(array('_', '-'), ' ', $file_display_name));
                    
                    $file_path = $docs_folder . $file;
                    
                    echo "<li><a href='$file_path' target='_blank'>$file_display_name (PDF)</a></li>";
                }
            } else {
                echo "<div class='no-files'>No documents available for download at the moment. Please upload documents to the <code>assets/docs/</code> folder.</div>";
            }
            ?>
        </ul>

        <a href="../index.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>