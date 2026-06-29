<?php
$page_title = "Search Samples";
include 'db.php';
include 'header.php';

$search_query = "";
$results = null;

if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    
    // Escaping to prevent SQL injection (standard secure PHP practice)
    $escaped_search = mysqli_real_escape_string($conn, $search_query);
    
    $query = "SELECT * FROM turmeric_samples 
              WHERE sample_id LIKE '%$escaped_search%' 
              OR location LIKE '%$escaped_search%' 
              OR sample_type LIKE '%$escaped_search%'
              ORDER BY sample_id ASC";
              
    $results = mysqli_query($conn, $query);
}
?>

<div class="section-header">
    <div class="section-title">
        <h2>Search Samples</h2>
        <p>Query by Sample ID, Type, or Geographical Origin/Brand</p>
    </div>
</div>

<!-- Search Input Console -->
<div class="search-container">
    <form method="GET" action="search.php">
        <div class="search-input-wrapper">
            <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" id="search" placeholder="Type sample ID (e.g. RT-2), location, or type..." value="<?php echo htmlspecialchars($search_query); ?>" required autocomplete="off">
            <button type="submit" class="search-btn">Search</button>
        </div>
    </form>
</div>

<!-- Results Area -->
<div>
    <?php if ($results !== null): ?>
        <h3 style="font-family: var(--font-heading); margin-bottom: 20px; font-weight: 600;">
            Search Results for "<?php echo htmlspecialchars($search_query); ?>" 
            <span style="color: var(--text-muted); font-size: 0.9rem;">(<?php echo mysqli_num_rows($results); ?> found)</span>
        </h3>
        
        <?php if (mysqli_num_rows($results) > 0): ?>
            <div class="search-cards-grid">
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <div class="search-result-card">
                        <div class="search-result-header">
                            <span class="search-result-title"><?php echo htmlspecialchars($row['sample_id']); ?></span>
                            <span class="search-result-type"><?php echo htmlspecialchars($row['sample_type']); ?></span>
                        </div>
                        
                        <div class="search-result-loc">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="vertical-align: text-top; margin-right: 4px; color: var(--primary);"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            Origin/Brand: <strong><?php echo htmlspecialchars($row['location']); ?></strong>
                        </div>
                        
                        <!-- Core element levels display -->
                        <div class="search-result-metrics">
                            <div class="metric-box">
                                <div class="metric-lbl">Fe</div>
                                <div class="metric-val" style="color: <?php echo $row['fe'] > 300 ? 'var(--danger)' : 'var(--text-primary)'; ?>;">
                                    <?php echo number_format($row['fe'], 1); ?>
                                </div>
                            </div>
                            <div class="metric-box">
                                <div class="metric-lbl">Zn</div>
                                <div class="metric-val" style="color: <?php echo $row['zn'] > 100 ? 'var(--danger)' : 'var(--text-primary)'; ?>;">
                                    <?php echo number_format($row['zn'], 1); ?>
                                </div>
                            </div>
                            <div class="metric-box">
                                <div class="metric-lbl">Cr</div>
                                <div class="metric-val" style="color: <?php echo $row['cr'] > 2.3 ? 'var(--danger)' : 'var(--text-primary)'; ?>;">
                                    <?php echo number_format($row['cr'], 2); ?>
                                </div>
                            </div>
                            <div class="metric-box">
                                <div class="metric-lbl">Br</div>
                                <div class="metric-val" style="color: <?php echo $row['br'] > 4.0 ? 'var(--danger)' : 'var(--text-primary)'; ?>;">
                                    <?php echo number_format($row['br'], 2); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px; display: flex; justify-content: flex-end; gap: 10px;">
                            <a href="edit_sample.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 6px 14px; font-size: 0.8rem;">
                                Edit
                            </a>
                            <a href="delete_sample.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-delete" data-sample-id="<?php echo htmlspecialchars($row['sample_id']); ?>" style="padding: 6px 14px; font-size: 0.8rem;">
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 40px; text-align: center; border-radius: var(--radius-md); color: var(--text-secondary);">
                No matching records found for "<strong><?php echo htmlspecialchars($search_query); ?></strong>". Try searching for "RT" or "West Bengal".
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 50px; text-align: center; border-radius: var(--radius-md); color: var(--text-muted); max-width: 600px; margin: 0 auto;">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom: 15px; color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p>Enter search criteria above to search the Turmeric Element database.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
