<?php
$page_title = "Add Sample";
include 'db.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize metadata inputs
    $sample_id = trim($_POST['sample_id']);
    $sample_type = $_POST['sample_type'];
    $location = trim($_POST['location']);
    $country = isset($_POST['country']) ? trim($_POST['country']) : 'India';
    
    // Collect element values, fallback to 0.0 if empty/invalid
    $ba = !empty($_POST['ba']) ? floatval($_POST['ba']) : 0.0;
    $br = !empty($_POST['br']) ? floatval($_POST['br']) : 0.0;
    $ca = !empty($_POST['ca']) ? floatval($_POST['ca']) : 0.0;
    $co = !empty($_POST['co']) ? floatval($_POST['co']) : 0.0;
    $cr = !empty($_POST['cr']) ? floatval($_POST['cr']) : 0.0;
    $fe = !empty($_POST['fe']) ? floatval($_POST['fe']) : 0.0;
    $k_value = !empty($_POST['k_value']) ? floatval($_POST['k_value']) : 0.0;
    $na_value = !empty($_POST['na_value']) ? floatval($_POST['na_value']) : 0.0;
    $rb = !empty($_POST['rb']) ? floatval($_POST['rb']) : 0.0;
    $sc = !empty($_POST['sc']) ? floatval($_POST['sc']) : 0.0;
    $sm = !empty($_POST['sm']) ? floatval($_POST['sm']) : 0.0;
    $zn = !empty($_POST['zn']) ? floatval($_POST['zn']) : 0.0;

    // Validate inputs
    if (empty($sample_id) || empty($location)) {
        $error_msg = "Sample ID and Location/Brand are required.";
    } else {
        // Check if sample ID already exists
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM turmeric_samples WHERE sample_id = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $sample_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_msg = "Sample ID '{$sample_id}' already exists in the database. Please use a unique ID.";
            mysqli_stmt_close($check_stmt);
        } else {
            mysqli_stmt_close($check_stmt);
            
            // Insert Statement
            $insert_query = "INSERT INTO turmeric_samples 
                             (sample_id, sample_type, location, country, ba, br, ca, co, cr, fe, k_value, na_value, rb, sc, sm, zn) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "ssssdddddddddddd", 
                $sample_id, $sample_type, $location, $country,
                $ba, $br, $ca, $co, $cr, $fe, $k_value, $na_value, $rb, $sc, $sm, $zn
            );
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: samples.php?status=success");
                exit();
            } else {
                $error_msg = "Database Error: " . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
}

include 'header.php';
?>

<div class="section-header">
    <div class="section-title">
        <h2>Add Turmeric Sample</h2>
        <p>Insert new experimental data into the analysis portal</p>
    </div>
    <div>
        <a href="samples.php" class="btn btn-secondary">Back to Database</a>
    </div>
</div>

<div class="form-card">
    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #fca5a5; margin-bottom: 25px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_sample.php" class="sample-form">
        <div class="form-grid">
            
            <!-- Section 1: General Info -->
            <div class="form-group-full">
                <div class="form-section-title">Sample Identity & Details</div>
            </div>
            
            <div class="form-group">
                <label for="sample_id">Sample ID *</label>
                <input type="text" name="sample_id" id="sample_id" class="form-control" placeholder="e.g. RT-5, BT-7" required autocomplete="off">
            </div>
            
            <div class="form-group">
                <label for="sample_type">Sample Type *</label>
                <select name="sample_type" id="sample_type" class="form-control" required>
                    <option value="Raw">Raw Turmeric</option>
                    <option value="Branded">Branded Powder</option>
                </select>
            </div>
            
            <div class="form-group form-group-full">
                <label for="location">Geographical Location or Brand Name *</label>
                <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Noida, West Bengal, Brand XYZ" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="country">Country *</label>
                <input type="text" name="country" id="country" class="form-control" placeholder="e.g. India, China, Turkey, Brazil" required autocomplete="off">
            </div>
            
            <!-- Section 2: Element Concentrations -->
            <div class="form-group-full" style="margin-top: 20px;">
                <div class="form-section-title">Elemental Concentrations (mg/kg unless specified)</div>
            </div>
            
            <div class="form-group">
                <label for="fe">Iron (Fe)</label>
                <input type="number" name="fe" id="fe" class="form-control" step="any" min="0" placeholder="e.g. 315.0" required>
            </div>
            
            <div class="form-group">
                <label for="zn">Zinc (Zn)</label>
                <input type="number" name="zn" id="zn" class="form-control" step="any" min="0" placeholder="e.g. 43.3" required>
            </div>
            
            <div class="form-group">
                <label for="cr">Chromium (Cr)</label>
                <input type="number" name="cr" id="cr" class="form-control" step="any" min="0" placeholder="e.g. 1.2" required>
            </div>
            
            <div class="form-group">
                <label for="br">Bromine (Br)</label>
                <input type="number" name="br" id="br" class="form-control" step="any" min="0" placeholder="e.g. 3.0" required>
            </div>
            
            <div class="form-group">
                <label for="ba">Barium (Ba)</label>
                <input type="number" name="ba" id="ba" class="form-control" step="any" min="0" placeholder="e.g. 9.1">
            </div>
            
            <div class="form-group">
                <label for="ca">Calcium (Ca) <span style="font-size: 10px; color: var(--text-muted);">(wt%)</span></label>
                <input type="number" name="ca" id="ca" class="form-control" step="any" min="0" placeholder="e.g. 0.34">
            </div>
            
            <div class="form-group">
                <label for="co">Cobalt (Co)</label>
                <input type="number" name="co" id="co" class="form-control" step="any" min="0" placeholder="e.g. 0.23">
            </div>
            
            <div class="form-group">
                <label for="k_value">Potassium (K) <span style="font-size: 10px; color: var(--text-muted);">(wt%)</span></label>
                <input type="number" name="k_value" id="k_value" class="form-control" step="any" min="0" placeholder="e.g. 2.90">
            </div>
            
            <div class="form-group">
                <label for="na_value">Sodium (Na)</label>
                <input type="number" name="na_value" id="na_value" class="form-control" step="any" min="0" placeholder="e.g. 151.0">
            </div>
            
            <div class="form-group">
                <label for="rb">Rubidium (Rb)</label>
                <input type="number" name="rb" id="rb" class="form-control" step="any" min="0" placeholder="e.g. 9.6">
            </div>
            
            <div class="form-group">
                <label for="sc">Scandium (Sc)</label>
                <input type="number" name="sc" id="sc" class="form-control" step="any" min="0" placeholder="e.g. 0.100">
            </div>
            
            <div class="form-group">
                <label for="sm">Samarium (Sm)</label>
                <input type="number" name="sm" id="sm" class="form-control" step="any" min="0" placeholder="e.g. 0.044">
            </div>
            
            <!-- Submit buttons -->
            <div class="form-group-full" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="reset" class="btn btn-secondary">Clear Form</button>
                <button type="submit" class="btn btn-primary">Add Sample Data</button>
            </div>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
