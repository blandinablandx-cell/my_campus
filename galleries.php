<?php
session_start();

// Njia ya kwenda kwenye folda ya picha za chuo
$image_folder = "assets/images/";

// Kama folda haipo, itengeneze au iweke tayari kusoma
if (!is_dir($image_folder)) {
    // Jaribu kutafuta kwa kuelekeza juu kama upo kwenye subfolder
    $image_folder = "../assets/images/";
}

// Chagua picha zenye miundo ya jpg, jpeg, png, au webp tu
$images = glob($image_folder . "*.{jpg,jpeg,png,webp}", GLOB_BRACE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Galleries - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        .navbar {
            background-color: #0d1b2a;
            color: #ffffff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 22px;
            color: #00b4d8;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }
        h2 {
            color: #0d1b2a;
            border-bottom: 2px solid #00b4d8;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }
        .gallery-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .gallery-caption {
            padding: 15px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            background-color: #fff;
        }
        .no-images {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            color: #0d1b2a;
            text-decoration: none;
            font-weight: 600;
        }
        .back-btn:hover {
            color: #00b4d8;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>My Campus - Galleries</h1>
        <div>
            <a href="index.php" style="color: white; text-decoration: none; font-weight: 600;">← Back to Home</a>
        </div>
    </div>

    <div class="container">
        <h2>Campus Environment & Banners Gallery</h2>
        <p>Explore vibrant moments, infrastructure, and promotional banners capturing life around My Campus University.</p>

        <div class="gallery-grid">
            <?php
            if (!empty($images) && count($images) > 0) {
                foreach ($images as $img_path) {
                    // Pata jina la picha bila extension ili utumie kama maelezo (caption)
                    $filename = pathinfo($img_path, PATHINFO_FILENAME);
                    $caption = ucwords(str_replace(array('_', '-'), ' ', $filename));

                    echo '<div class="gallery-item">';
                    echo '<img src="' . $img_path . '" alt="' . $caption . '">';
                    echo '<div class="gallery-caption">' . $caption . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-images" style="grid-column: 1 / -1;">No images found in the <code>assets/images/</code> folder. Please add some banners or pictures there.</div>';
            }
            ?>
        </div>

        <a href="index.php" class="back-btn">← Back to Dashboard</a>
    </div>

</body>
</html>