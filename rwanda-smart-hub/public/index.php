<?php

require_once "../languages/language.php";

$translations = $translations;

require_once "../config/database.php";

$search = $_GET["search"] ?? "";
$search = $conn->real_escape_string($search);

$province_filter = $_GET["province"] ?? "";
$province_filter = $conn->real_escape_string($province_filter);

$sector_filter = $_GET["sector"] ?? "";
$sector_filter = $conn->real_escape_string($sector_filter);

$category_filter = $_GET["category"] ?? "";
$category_filter = $conn->real_escape_string($category_filter);

$district_filter = $_GET["district"] ?? "";
$district_filter = $conn->real_escape_string($district_filter);

/* Get categories */
$categories = $conn->query(
    "SELECT * FROM categories ORDER BY name"
);

/* Search information */
if (
    $search !== "" ||
    $category_filter !== "" ||
    $province_filter !== "" ||
    $district_filter !== "" ||
    $sector_filter !== ""
) {

    $conditions = [];

    if ($search !== "") {

        $conditions[] = "
            (
                information.name LIKE '%$search%'
                OR categories.name LIKE '%$search%'
                OR locations.district LIKE '%$search%'
                OR locations.sector LIKE '%$search%'
                OR information.address LIKE '%$search%'
            )
        ";

    }

    if ($category_filter !== "") {

        $conditions[] =
            "information.category_id = '$category_filter'";

    }

if ($province_filter !== "") {
    $conditions[] = "locations.province = '$province_filter'";
}

    if ($district_filter !== "") {

        $conditions[] =
            "locations.district = '$district_filter'";

    }

    if ($sector_filter !== "") {
    $conditions[] = "locations.sector = '$sector_filter'";
}

    $where = implode(" AND ", $conditions);

    $sql = "
        SELECT
            information.*,
            categories.name AS category_name,
            locations.province,
            locations.district,
            locations.sector
        FROM information

        INNER JOIN categories
            ON information.category_id = categories.id

        LEFT JOIN locations
            ON information.location_id = locations.id

        WHERE $where
AND information.verification_status != 'archived'

        ORDER BY information.name
    ";

} else {

    $sql = "
        SELECT
            information.*,
            categories.name AS category_name,
            locations.province,
            locations.district,
            locations.sector
        FROM information

        INNER JOIN categories
            ON information.category_id = categories.id

        LEFT JOIN locations
            ON information.location_id = locations.id
WHERE information.verification_status != 'archived'
        ORDER BY information.created_at DESC
    ";

}
$information = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>
<section class="hero">
    <h1><?php echo $translations["hero_title"]; ?></h1>

    <p>
        <?php echo $translations["hero_text"]; ?>
    </p>

    <a href="#information" class="hero-button">
        <?php echo $translations["explore_information"]; ?>
    </a>
</section>
<header>

    <div class="container">

        <nav class="navbar">

            <div class="logo">
               <a href="index.php" class="site-brand">
    <img src="../assets/logo.png.png" alt="Rwanda Smart Hub Logo">
    <span>Rwanda Smart Hub</span>
</a>
            </div>

           <div class="nav-links">

    <a href="index.php">
    <?php echo $translations["home"]; ?>
</a>

<a href="#categories">
    <?php echo $translations["categories"]; ?>
</a>

<a href="#information">
    <?php echo $translations["information"]; ?>
</a>

<a href="about.php">
    <?php echo $translations["about"]; ?>
</a>

    <div class="language-links">

        <a href="?lang=rw">
             RW
        </a>

        <a href="?lang=en">
             ENG
        </a>

        <a href="?lang=fr">
             FR
        </a>

    </div>

</div>

        </nav>

    </div>

</header>


<main>

    <section class="hero">

        <div class="container">

            <h1>
                Find Information Across Rwanda
            </h1>

            <p>
                Search for useful information, services,
                businesses, places and more.
            </p>


           <form method="GET"
      class="search-box">

    <input
        type="text"
        name="search"
        placeholder="<?php echo $translations["search"]; ?>"
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <select name="category">
<option value="">
    <?php echo $translations["all_categories"]; ?>
</option>
        <?php

        $category_filter =
            $_GET["category"] ?? "";

        $category_filter =
            $conn->real_escape_string(
                $category_filter
            );

        $category_options =
            $conn->query(
                "SELECT * FROM categories ORDER BY name"
            );

        while (
            $option =
            $category_options->fetch_assoc()
        ) {

            $selected = "";

            if (
                $category_filter ==
                $option["id"]
            ) {
                $selected = "selected";
            }

            echo "<option value='"
                . $option["id"]
                . "' "
                . $selected
                . ">"
                . htmlspecialchars(
                    $option["name"]
                )
                . "</option>";
        }

        ?>

    </select>
    <select name="province">
    <option value="">
        <?php echo $translations["all_provinces"]; ?>
    </option>

    <?php
    $province_result = $conn->query(
        "SELECT DISTINCT province FROM locations ORDER BY province"
    );

    while ($province = $province_result->fetch_assoc()):
    ?>

        <option value="<?php echo htmlspecialchars($province["province"]); ?>"
            <?php echo ($province_filter === $province["province"]) ? "selected" : ""; ?>>
            <?php echo htmlspecialchars($province["province"]); ?>
        </option>

    <?php endwhile; ?>
</select>

    <select name="district">

        <option value="">
    <?php echo $translations["all_districts"]; ?>
</option>

        <?php
$province_filter = $_GET["province"] ?? "";
$province_filter = $conn->real_escape_string($province_filter);

        $district_filter =
            $_GET["district"] ?? "";

        $district_filter =
            $conn->real_escape_string(
                $district_filter
            );

        $district_options =
            $conn->query(
                "SELECT DISTINCT district
                 FROM locations
                 ORDER BY district"
            );

        while (
            $district =
            $district_options->fetch_assoc()
        ) {

            $district_name =
                $district["district"];

            $selected = "";

            if (
                $district_filter ===
                $district_name
            ) {
                $selected = "selected";
            }

            echo "<option value='"
                . htmlspecialchars($district_name)
                . "' "
                . $selected
                . ">"
                . htmlspecialchars($district_name)
                . "</option>";
        }

        ?>

    </select>
    <select name="sector" id="sector">
    <option value="">All Sectors</option>

    <?php
    $sector_result = $conn->query(
        "SELECT DISTINCT district, sector
         FROM locations
         WHERE sector IS NOT NULL
         AND sector != ''
         ORDER BY district, sector"
    );

    while ($sector = $sector_result->fetch_assoc()):
    ?>

        <option
            value="<?php echo htmlspecialchars($sector["sector"]); ?>"
            data-district="<?php echo htmlspecialchars($sector["district"]); ?>"
            <?php echo ($sector_filter === $sector["sector"]) ? "selected" : ""; ?>
        >
            <?php echo htmlspecialchars($sector["sector"]); ?>
        </option>

    <?php endwhile; ?>
</select>
    <button type="submit">
    <?php echo $translations["search_button"]; ?>
</button>

  <a href="index.php" class="clear-button">
    <?php echo $translations["clear_filters"]; ?>
</a>
</form>

        </div>

    </section>


    <section class="container" id="categories">

        <h2 class="section-title">
            Explore Categories
        </h2>


        <div class="categories">

            <?php

            if ($categories && $categories->num_rows > 0) {

                while ($category = $categories->fetch_assoc()) {

               $category_name = $category["name"];

$icons = [
    "Health" => "🏥",
    "Education" => "🎓",
    "Jobs" => "💼",
    "Services" => "🛠️",
    "Transport" => "🚌",
    "Businesses" => "🏪",
    "Emergency" => "🚨",
    "Places" => "📍"
];

$icon = $icons[$category_name] ?? "📂";

echo "
<a href='?search="
    . urlencode($category_name)
    . "' class='category-card'>

    <h3>"
    . $icon
    . " "
    . htmlspecialchars($category_name)
    .
    "</h3>

    <p>"
    . htmlspecialchars(
        $category["description"] ?? ""
    )
    .
    "</p>

</a>
";
                }

            }

            ?>

        </div>

    </section>


    <section class="container" id="information">
<?php if (
    $search !== "" ||
    $category_filter !== "" ||
    $district_filter !== ""
): ?>

    <h2 class="section-title">
        <?php echo $translations["search_results"]; ?>
    </h2>

<?php else: ?>

    <h2 id="information">
    <?php echo $translations["featured_information"]; ?>
</h2>

<?php endif; ?>


<p class="result-count">

    <?php

    if ($information) {

        echo $information->num_rows;

        if ($information->num_rows === 1) {
            echo " result found";
        } else {
            echo " results found";
        }

    }

    ?>

</p>


        <?php

        if ($information && $information->num_rows > 0) {

            while ($row = $information->fetch_assoc()) {

        ?>

                <div class="information-card">

                    <h2>
                        <?php
                        echo htmlspecialchars(
                            $row["name"]
                        );
                        ?>
                    </h2>


                    <p>

                        <strong>Category:</strong>

                        <?php
                        echo htmlspecialchars(
                            $row["category_name"]
                        );
                        ?>

                    </p>
                    <p>
<?php
    echo htmlspecialchars(
        $row["province"] ?? ""
    );
    ?>
    </p>

                    <p>

                        <strong>Location:</strong>

                        <?php
                        echo htmlspecialchars(
                            $row["district"] ?? ""
                        );
                        ?>

                        <?php if (!empty($row["sector"])): ?>

                            →
                            <?php
                            echo htmlspecialchars(
                                $row["sector"]
                            );
                            ?>

                        <?php endif; ?>

                    </p>
                    <!-- <p>
    <strong>Phone:</strong>

    <?php
    echo htmlspecialchars($row["phone"]);
    ?>
</p> -->



                    <?php if (!empty($row["phone"])): ?>

                        <p>

                            <strong>Phone:</strong>

                            <?php
                            echo htmlspecialchars(
                                $row["phone"]
                            );
                            ?>

                        </p>

                    <?php endif; ?>


                    <?php if (!empty($row["address"])): ?>

                        <p>

                            <strong>Address:</strong>

                            <?php
                            echo htmlspecialchars(
                                $row["address"]
                            );
                            ?>

                        </p>

                    <?php endif; ?>


                    <?php if (!empty($row["description"])): ?>

                        <p>

                            <?php
                            echo htmlspecialchars(
                                $row["description"]
                            );
                            ?>

                        </p>

                    <?php endif; ?>


                    <?php

$status = $row["verification_status"];

$status_class = "status-unverified";

if ($status === "verified") {
    $status_class = "status-verified";
} elseif ($status === "needs review") {
    $status_class = "status-review";
} elseif ($status === "archived") {
    $status_class = "status-archived";
}

?>
<?php

$status_text = $status;

if ($status === "verified") {
    $status_text = $translations["verified"];
} elseif ($status === "unverified") {
    $status_text = $translations["unverified"];
} elseif ($status === "needs review") {
    $status_text = $translations["needs_review"];
} elseif ($status === "archived") {
    $status_text = $translations["archived"];
}

?>

<p class="status <?php echo $status_class; ?>">

    <?php echo $status_text; ?>

</p>

<a
    href="details.php?id=<?php echo $row["id"]; ?>"
    class="details-button"
>
    View Details
</a>

                </div>

        <?php

            }

        } else {

        ?>

           <div class="information-card no-results">

    <h3>
    🔍 <?php echo $translations["no_information"]; ?>
</h3>

    <p>
        We couldn't find information matching
        your current search.
    </p>

    <?php if ($search !== ""): ?>

        <p>
            <strong>Keyword:</strong>
            <?php echo htmlspecialchars($search); ?>
        </p>

    <?php endif; ?>

    <?php if ($category_filter !== ""): ?>

        <p>
            <strong>Category:</strong>
            <?php

            $category_result =
                $conn->query(
                    "SELECT name FROM categories
                     WHERE id = '$category_filter'"
                );

            if (
                $category_result &&
                $category_result->num_rows > 0
            ) {

                $category_row =
                    $category_result->fetch_assoc();

                echo htmlspecialchars(
                    $category_row["name"]
                );

            }

            ?>
        </p>

    <?php endif; ?>

    <?php if ($district_filter !== ""): ?>

        <p>
            <strong>District:</strong>
            <?php echo htmlspecialchars($district_filter); ?>
        </p>

    <?php endif; ?>

    <a href="index.php" class="clear-button">

    <?php echo $translations["clear_filters"]; ?>

</a>

</div>

        <?php

        }

        ?>

    </section>

</main>


<footer>

    <p>
        © <?php echo date("Y"); ?>
        Rwanda Smart Hub
    </p>

    <p>
        Prototype — demonstration information only
    </p>

</footer>
<script>
const districtSelect = document.querySelector('select[name="district"]');
const sectorSelect = document.querySelector('select[name="sector"]');

function updateSectors() {
    const selectedDistrict = districtSelect.value;

    Array.from(sectorSelect.options).forEach(option => {
        if (option.value === "") {
            option.style.display = "";
            return;
        }

        option.style.display =
            option.dataset.district === selectedDistrict
                ? ""
                : "none";
    });

    sectorSelect.value = "";
}

districtSelect.addEventListener("change", updateSectors);

updateSectors();
</script>

</body>

</html>