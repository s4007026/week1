<?php
include 'includes/header.inc';  // Header section including DOCTYPE, head, and start of body
include 'includes/nav.inc';     // Navigation section
?>

<main>
    <h2>Discover Pets Victoria</h2>
    <p>Pets Victoria is a dedicated pet adoption organization based in Victoria, Australia, focused on providing a safe and loving environment for pets in need. With a compassionate approach, Pets Victoria works tirelessly to rescue, rehabilitate, and rehome dogs, cats, and other animals. Their mission is to connect these deserving pets with caring individuals and families, creating lifelong bonds. The organization offers a range of services, including adoption counseling, pet education, and community support programs, all aimed at promoting responsible pet ownership and reducing the number of homeless animals.</p>
    
    <img src="images/pets.jpeg" alt="Pets Running" class="pets-image">

    <table>
        <thead>
            <tr>
                <th>Pet</th>
                <th>Type</th>
                <th>Age</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="details.php?id=1">Milo</a></td>
                <td>Cat</td>
                <td>3 months</td>
                <td>Melbourne CBD</td>
            </tr>
            <tr>
                <td><a href="details.php?id=2">Baxter</a></td>
                <td>Dog</td>
                <td>5 months</td>
                <td>Cape Woolamai</td>
            </tr>
            <tr>
                <td><a href="details.php?id=3">Luna</a></td>
                <td>Cat</td>
                <td>1 month</td>
                <td>Ferntree Gully</td>
            </tr>
            <tr>
                <td><a href="details.php?id=4">Willow</a></td>
                <td>Dog</td>
                <td>48 months</td>
                <td>Marysville</td>
            </tr>
            <tr>
                <td><a href="details.php?id=5">Oliver</a></td>
                <td>Cat</td>
                <td>12 months</td>
                <td>Grampians</td>
            </tr>
            <tr>
                <td><a href="details.php?id=6">Bella</a></td>
                <td>Dog</td>
                <td>10 months</td>
                <td>Carlton</td>
            </tr>
        </tbody>
    </table>
</main>

<?php
include 'includes/footer.inc';  // Footer section with closing body and HTML tags
?>
