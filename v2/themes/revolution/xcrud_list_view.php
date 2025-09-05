<?php 
// Revolution Theme - Innovative List View
// Check view mode from GET parameter or session
$is_grid_view = (isset($_GET['view']) && $_GET['view'] == 'grid') || 
                (isset($_POST['view']) && $_POST['view'] == 'grid');

// Store view mode in hidden field for AJAX persistence
?>

<div class="revo-container">
    <!-- Sidebar -->
    <aside class="revo-sidebar">
        <div class="revo-sidebar-header">
            <h3 class="revo-brand">
                <span class="revo-brand-icon">◈</span>
                xCrud<span class="revo-brand-accent">Revolution</span>
            </h3>
            <button class="revo-theme-toggle" onclick="toggleRevolutionTheme()">
                <span class="revo-theme-icon">🌙</span>
            </button>
        </div>
        
        <?php if ($this->is_create or $this->is_csv or $this->is_print): ?>
        <div class="revo-actions-section">
            <h4 class="revo-section-title">Quick Actions</h4>
            <?php echo $this->add_button('revo-action-card', '<span class="revo-icon">+</span><span>Create New</span>'); ?>
            <?php echo $this->csv_button('revo-action-card revo-action-export', '<span class="revo-icon">↓</span><span>Export CSV</span>'); ?>
            <?php echo $this->print_button('revo-action-card revo-action-print', '<span class="revo-icon">⎙</span><span>Print</span>'); ?>
        </div>
        <?php endif; ?>
        
        <div class="revo-filter-section">
            <h4 class="revo-section-title">Filters & Search</h4>
            <?php echo $this->render_search(); ?>
        </div>
        
        <div class="revo-view-toggle">
            <button class="revo-view-btn <?php echo !$is_grid_view ? 'active' : ''; ?>" onclick="setRevolutionView('table')">
                <span class="revo-icon">☰</span> List
            </button>
            <button class="revo-view-btn <?php echo $is_grid_view ? 'active' : ''; ?>" onclick="setRevolutionView('grid')">
                <span class="revo-icon">⊞</span> Grid
            </button>
        </div>
        
        <div class="revo-stats">
            <?php echo $this->render_benchmark(); ?>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="revo-main">
        <!-- Header Bar -->
        <header class="revo-header">
            <?php echo $this->render_table_name(); ?>
            <div class="revo-header-controls">
                <?php echo $this->render_limitlist(false); ?>
            </div>
        </header>
        
        <!-- Content Area -->
        <div class="revo-content <?php echo $is_grid_view ? 'revo-grid-view' : 'revo-table-view'; ?>">
            <?php if (!$is_grid_view): ?>
                <!-- Table View -->
                <div class="revo-table-wrapper">
                    <table class="revo-table">
                        <thead>
                            <?php echo $this->render_grid_head('tr', 'th'); ?>
                        </thead>
                        <tbody>
                            <?php echo $this->render_grid_body('tr', 'td'); ?>
                        </tbody>
                        <tfoot>
                            <?php echo $this->render_grid_footer('tr', 'td'); ?>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <!-- Grid View -->
                <div class="revo-grid-container">
                    <?php 
                    // Get data using new getter methods
                    $data = $this->get_grid_data();
                    $cols = $this->get_columns_data();
                    $primary_key = $this->get_primary_key();
                    
                    // Debug: Check what we're getting
                    if ($data === null) {
                        // Try direct access as fallback
                        try {
                            $reflection = new ReflectionClass($this);
                            $property = $reflection->getProperty('result_list');
                            $property->setAccessible(true);
                            $data = $property->getValue($this);
                        } catch (Exception $e) {
                            $data = null;
                        }
                    }
                    
                    // Debug output (remove in production)
                    // echo '<!-- Debug: Data count = ' . (is_array($data) ? count($data) : 'null') . ' -->';
                    // echo '<!-- Debug: Primary key = ' . $primary_key . ' -->';
                    
                    if (!empty($data)):
                        foreach($data as $row):
                            // Get all row keys for display
                            $row_keys = array_keys($row);
                    ?>
                    <div class="revo-grid-card">
                        <div class="revo-grid-card-header">
                            <h3 class="revo-grid-card-title">
                                <?php 
                                // Display first non-primary field as title
                                $title = '';
                                if (!empty($row_keys)) {
                                    // Try to find a good title field
                                    foreach ($row_keys as $key) {
                                        if ($key != $primary_key && isset($row[$key]) && !empty($row[$key])) {
                                            $title = $row[$key];
                                            break;
                                        }
                                    }
                                    if (empty($title) && isset($row[$primary_key])) {
                                        $title = 'Record #' . $row[$primary_key];
                                    }
                                }
                                echo htmlspecialchars(substr($title, 0, 50));
                                ?>
                                <?php if(isset($row[$primary_key])): ?>
                                <span class="revo-grid-card-badge">#<?php echo htmlspecialchars($row[$primary_key]); ?></span>
                                <?php endif; ?>
                            </h3>
                        </div>
                        <div class="revo-grid-card-body">
                            <?php 
                            $field_count = 0;
                            // Display fields from row data directly
                            foreach($row as $field_name => $field_value):
                                if($field_count >= 5) break; // Show max 5 fields
                                
                                // Skip primary key field
                                if($field_name == $primary_key || 
                                   stripos($field_name, 'primary') !== false && stripos($field_name, 'key') !== false) {
                                    continue;
                                }
                                
                                // Clean field name - remove all prefixes (Rel., table names, etc.)
                                $clean_field_name = $field_name;
                                
                                // Remove "Rel." prefix if present
                                if (stripos($clean_field_name, 'rel.') === 0) {
                                    $clean_field_name = substr($clean_field_name, 4);
                                }
                                
                                // Remove table prefixes (anything before last dot)
                                if (strpos($clean_field_name, '.') !== false) {
                                    $parts = explode('.', $clean_field_name);
                                    $clean_field_name = end($parts);
                                }
                                
                                // Skip if this is a system field
                                if (in_array(strtolower($clean_field_name), ['xcrud', 'primary_key', 'key'])) {
                                    continue;
                                }
                                
                                // Get label from columns data
                                $label = null;
                                
                                // Try multiple keys to find the label
                                $keys_to_try = [
                                    $clean_field_name,
                                    $field_name,
                                    'Rel.' . $clean_field_name,
                                    str_replace('.', '_', $field_name)
                                ];
                                
                                foreach ($keys_to_try as $key) {
                                    if (!empty($cols) && isset($cols[$key]) && isset($cols[$key]['label'])) {
                                        $label = $cols[$key]['label'];
                                        break;
                                    }
                                }
                                
                                // If no label found, beautify the clean field name
                                if (!$label) {
                                    // Convert snake_case to Title Case
                                    $label = ucwords(str_replace('_', ' ', $clean_field_name));
                                    // Handle camelCase
                                    $label = preg_replace('/([a-z])([A-Z])/', '$1 $2', $label);
                                    // Clean up common patterns
                                    $label = str_replace(['Id ', ' Id'], ['ID ', ' ID'], $label);
                                }
                            ?>
                            <div class="revo-grid-field">
                                <span class="revo-grid-field-label"><?php echo htmlspecialchars($label); ?>:</span>
                                <span class="revo-grid-field-value">
                                    <?php 
                                    $display_value = is_array($field_value) ? json_encode($field_value) : (string)$field_value;
                                    echo htmlspecialchars(substr($display_value, 0, 50));
                                    if(strlen($display_value) > 50) echo '...';
                                    ?>
                                </span>
                            </div>
                            <?php 
                                $field_count++;
                            endforeach;
                            ?>
                        </div>
                        <div class="revo-grid-card-footer xcrud-actions">
                            <?php 
                            $primary_val = isset($row[$primary_key]) ? $row[$primary_key] : '';
                            ?>
                            <?php if($this->is_view): ?>
                            <a href="javascript:void(0);" 
                               class="revo-btn revo-view xcrud-action" 
                               data-task="view" 
                               data-primary="<?php echo htmlspecialchars($primary_val); ?>">
                                <i class="icon-eye"></i> View
                            </a>
                            <?php endif; ?>
                            <?php if($this->is_edit): ?>
                            <a href="javascript:void(0);" 
                               class="revo-btn revo-edit xcrud-action" 
                               data-task="edit" 
                               data-primary="<?php echo htmlspecialchars($primary_val); ?>">
                                <i class="icon-pencil"></i> Edit
                            </a>
                            <?php endif; ?>
                            <?php if($this->is_remove): ?>
                            <a href="javascript:void(0);" 
                               class="revo-btn revo-delete xcrud-action" 
                               data-task="remove" 
                               data-primary="<?php echo htmlspecialchars($primary_val); ?>"
                               data-confirm="Are you sure?">
                                <i class="icon-remove"></i> Delete
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <div class="revo-grid-message">
                        <span class="revo-icon" style="font-size: 48px; display: block; margin-bottom: 16px;">📭</span>
                        <p>No records found</p>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Footer with Pagination -->
        <footer class="revo-footer">
            <?php echo $this->render_pagination(); ?>
        </footer>
    </main>
</div>

<!-- Revolution Theme Scripts -->
<script>
function toggleRevolutionTheme() {
    document.body.classList.toggle('revo-dark-mode');
    const icon = document.querySelector('.revo-theme-icon');
    icon.textContent = document.body.classList.contains('revo-dark-mode') ? '☀️' : '🌙';
    localStorage.setItem('revo-theme', document.body.classList.contains('revo-dark-mode') ? 'dark' : 'light');
}

function setRevolutionView(view) {
    const url = new URL(window.location);
    url.searchParams.set('view', view);
    // Save in session storage to persist across AJAX calls
    sessionStorage.setItem('revo-view-mode', view);
    window.location = url;
}

// Load saved theme and view mode
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('revo-theme') === 'dark') {
        document.body.classList.add('revo-dark-mode');
        document.querySelector('.revo-theme-icon').textContent = '☀️';
    }
    
    // Get current view mode
    const getCurrentViewMode = function() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('view') || sessionStorage.getItem('revo-view-mode') || 'table';
    };
    
    // Restore view mode from session storage
    const savedView = getCurrentViewMode();
    sessionStorage.setItem('revo-view-mode', savedView);
    
    if (savedView && !window.location.search.includes('view=')) {
        const url = new URL(window.location);
        url.searchParams.set('view', savedView);
        history.replaceState(null, '', url);
    }
    
    // Intercept all clicks on pagination links
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.xcrud-pagination a, .revo-pagination a, .revo-page-item');
        if (paginationLink && paginationLink.href) {
            e.preventDefault();
            const viewMode = getCurrentViewMode();
            
            // If it's a javascript link, extract the data and add view param
            if (paginationLink.href.includes('javascript:')) {
                const onclick = paginationLink.getAttribute('onclick');
                if (onclick) {
                    // Modify the onclick to include view parameter
                    const modifiedOnclick = onclick.replace(/\{([^}]*)\}/, function(match, p1) {
                        return '{' + p1 + ",view:'" + viewMode + "'}";
                    });
                    eval(modifiedOnclick);
                }
            } else {
                // For regular links, add view parameter
                const url = new URL(paginationLink.href);
                url.searchParams.set('view', viewMode);
                window.location = url;
            }
        }
    }, true);
    
    // Override Xcrud.request to always include view mode
    const originalRequest = window.Xcrud ? window.Xcrud.request : null;
    if (originalRequest) {
        window.Xcrud.request = function(container, data) {
            // Add current view mode to data
            const viewMode = getCurrentViewMode();
            
            if (typeof data === 'object' && data !== null) {
                data.view = viewMode;
            } else if (typeof data === 'string') {
                data += '&view=' + viewMode;
            }
            return originalRequest.call(this, container, data);
        };
    }
    
    // Also override list_data to ensure view is included
    const originalListData = window.Xcrud ? window.Xcrud.list_data : null;
    if (originalListData) {
        window.Xcrud.list_data = function(container, element) {
            const data = originalListData.call(this, container, element);
            if (data && typeof data === 'object') {
                data.view = getCurrentViewMode();
            }
            return data;
        };
    }
});
</script>