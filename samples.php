<?php
$page_title = "Samples Database";
include 'db.php';
include 'header.php';

// Get current filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Prepare query based on filter
$query = "SELECT * FROM turmeric_samples";
$whereClauses = [];

if ($filter === 'Raw') {
    $whereClauses[] = "sample_type = 'Raw'";
} elseif ($filter === 'Branded') {
    $whereClauses[] = "sample_type = 'Branded'";
} elseif ($filter !== 'all') {
    $country_name = mysqli_real_escape_string($conn, $filter);
    $whereClauses[] = "country = '$country_name'";
}

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(' AND ', $whereClauses);
}

$query .= " ORDER BY sample_id ASC";
$result = mysqli_query($conn, $query);

// Fetch counts for badges
$count_all = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM turmeric_samples"));
$count_raw = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM turmeric_samples WHERE sample_type = 'Raw'"));
$count_branded = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM turmeric_samples WHERE sample_type = 'Branded'"));
$country_summary_res = mysqli_query($conn, "SELECT country, COUNT(*) as sample_count FROM turmeric_samples GROUP BY country ORDER BY country ASC");
$country_tabs = [];
while ($country_row = mysqli_fetch_assoc($country_summary_res)) {
    $country_tabs[] = $country_row;
}

// WHO/FAO limits definitions
$limits = [
    'fe' => 300.0,
    'zn' => 100.0,
    'cr' => 2.3,
    'br' => 4.0
];
?>

<div class="section-header">
    <div class="section-title">
        <h2>Sample Database</h2>
        <p>Comprehensive elemental analysis of turmeric samples</p>
    </div>
    <div>
        <a href="add_sample.php" class="btn btn-primary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Add New Sample
        </a>
    </div>
</div>

<!-- Success message handler -->
<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="alert alert-success">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        Action completed successfully! Database updated.
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="samples.php?filter=all" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
        All Samples <span class="badge"><?php echo $count_all; ?></span>
    </a>
    <?php foreach ($country_tabs as $country_tab): ?>
        <a href="samples.php?filter=<?php echo urlencode($country_tab['country']); ?>" class="filter-tab <?php echo $filter === $country_tab['country'] ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($country_tab['country']); ?> <span class="badge"><?php echo $country_tab['sample_count']; ?></span>
        </a>
    <?php endforeach; ?>
    <a href="samples.php?filter=Raw" class="filter-tab <?php echo $filter === 'Raw' ? 'active' : ''; ?>">
        Raw Turmeric <span class="badge"><?php echo $count_raw; ?></span>
    </a>
    <a href="samples.php?filter=Branded" class="filter-tab <?php echo $filter === 'Branded' ? 'active' : ''; ?>">
        Branded Powder <span class="badge"><?php echo $count_branded; ?></span>
    </a>
</div>

<!-- Table Wrapper -->
<div class="table-wrapper">
    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>Sample ID</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th>Location/Brand</th>
                    <th>Fe <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Zn <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Cr <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Br <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Ba <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Ca <span style="font-size: 10px; color: var(--text-muted);">(wt%)</span></th>
                    <th>Co <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>K <span style="font-size: 10px; color: var(--text-muted);">(wt%)</span></th>
                    <th>Na <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Rb <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Sc <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th>Sm <span style="font-size: 10px; color: var(--text-muted);">(mg/kg)</span></th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="sample-id-cell"><?php echo htmlspecialchars($row['sample_id']); ?></td>
                            <td>
                                <span class="meta-badge" style="background: <?php echo $row['sample_type'] === 'Raw' ? 'rgba(245, 158, 11, 0.08)' : 'rgba(16, 185, 129, 0.08)'; ?>; border-color: <?php echo $row['sample_type'] === 'Raw' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(16, 185, 129, 0.2)'; ?>; color: <?php echo $row['sample_type'] === 'Raw' ? 'var(--primary)' : 'var(--secondary)'; ?>;">
                                    <?php echo htmlspecialchars($row['sample_type']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['country'] ?? 'India'); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            
                            <!-- Iron -->
                            <td class="<?php echo $row['fe'] > $limits['fe'] ? 'exceeded-limit' : ''; ?>">
                                <?php echo number_format($row['fe'], 1); ?>
                            </td>
                            
                            <!-- Zinc -->
                            <td class="<?php echo $row['zn'] > $limits['zn'] ? 'exceeded-limit' : ''; ?>">
                                <?php echo number_format($row['zn'], 1); ?>
                            </td>
                            
                            <!-- Chromium -->
                            <td class="<?php echo $row['cr'] > $limits['cr'] ? 'exceeded-limit' : ''; ?>">
                                <?php echo number_format($row['cr'], 2); ?>
                            </td>
                            
                            <!-- Bromine -->
                            <td class="<?php echo $row['br'] > $limits['br'] ? 'exceeded-limit' : ''; ?>">
                                <?php echo number_format($row['br'], 2); ?>
                            </td>
                            
                            <!-- Remaining elements -->
                            <td><?php echo number_format($row['ba'], 2); ?></td>
                            <td><?php echo number_format($row['ca'], 2); ?>%</td>
                            <td><?php echo number_format($row['co'], 2); ?></td>
                            <td><?php echo number_format($row['k_value'], 2); ?>%</td>
                            <td><?php echo number_format($row['na_value'], 1); ?></td>
                            <td><?php echo number_format($row['rb'], 2); ?></td>
                            <td><?php echo number_format($row['sc'], 3); ?></td>
                            <td><?php echo number_format($row['sm'], 3); ?></td>
                            
                            <!-- Action Buttons -->
                            <td>
                                <div class="btn-group" style="justify-content: center;">
                                    <a href="edit_sample.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                                        Edit
                                    </a>
                                    <a href="delete_sample.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-delete" data-sample-id="<?php echo htmlspecialchars($row['sample_id']); ?>" style="padding: 6px 12px; font-size: 0.8rem;">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="16" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            No samples found in the database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px; display: flex; gap: 15px; align-items: center; justify-content: flex-end; font-size: 0.8rem; color: var(--text-muted);">
    <div style="display: flex; align-items: center; gap: 6px;">
        <span style="display: inline-block; width: 12px; height: 12px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 2px;"></span>
        Exceeds WHO/FAO safe limit (Fe > 300, Zn > 100, Cr > 2.3, Br > 4.0 mg/kg)
    </div>
</div>

<?php include 'footer.php'; ?>
