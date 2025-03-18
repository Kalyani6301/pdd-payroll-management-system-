<div class="d-flex">
    <?php include 'sidebar.php'; ?>
    <!-- Punch In / Punch Out Section -->

<!-- jQuery CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container-fluid dashboard-container">
      <h2 class="mb-4">MY PROFILE</h2>
      <form method="POST" class="card p-4 shadow">
        <div class="row">
        <?php foreach ($employee as $name => $value) {
    $safe_value = htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); // Default to empty string if NULL
    echo '<div class="col-md-3 mb-3">
            <label for="' . $name . '" class="form-label">' . ucfirst(str_replace('_', ' ', $name)) . '</label>
            <input type="text" name="' . $name . '" class="form-control" value="' . $safe_value . '" required>
          </div>';
} ?>

        </div>
        <button type="submit" class="btn btn-primary d-block m-auto">Save Changes</button>
      </form>
    </div>
</div>