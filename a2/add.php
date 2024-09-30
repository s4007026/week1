<?php
include 'includes/header.inc';  // Header section including DOCTYPE, head, and start of body
include 'includes/nav.inc';     // Navigation section
?>

<main>
    <h2>Add a Pet</h2>
    <p>You can add a new pet here.</p>
    
    <form action="submit_pet.php" method="post" enctype="multipart/form-data">
        <label for="pet-name">Provide a name for the pet *</label>
        <input type="text" id="pet-name" name="pet-name" required>

        <label for="pet-type">Type *</label>
        <select id="pet-type" name="pet-type" required>
            <option value="">--Choose an option--</option>
            <option value="cat">Cat</option>
            <option value="dog">Dog</option>
            <option value="other">Other</option>
        </select>

        <label for="description">Description *</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="image">Select an Image *</label>
        <input type="file" id="image" name="image" required>

        <label for="caption">Image Caption *</label>
        <input type="text" id="caption" name="caption" required>

        <label for="age">Age (months) *</label>
        <input type="number" id="age" name="age" required>

        <label for="location">Location *</label>
        <input type="text" id="location" name="location" required>

        <div class="form-actions">
            <button type="submit">✔️ Submit</button>
            <button type="reset">✖️ Clear</button>
        </div>
    </form>
</main>

<?php
include 'includes/footer.inc';  // Footer section with closing body and HTML tags
?>
