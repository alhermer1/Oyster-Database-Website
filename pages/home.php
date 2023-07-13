<?php

const Largeness = array(
  1 => 'Small',
  2 => 'Medium',
  3 => 'Large'
);

const Available = array(
  1 => 'Out Of Stock',
  2 => 'Running Low',
  3 => 'In Stock'
);

$show_confirmation = False;

$form_feedback_classes = array(
  'name' => 'hidden',
  'size' => 'hidden',
  'price' => 'hidden',
  'origin' => 'hidden',
  'availability' => 'hidden',
  'farmer' => 'hidden',
  'farmp' => 'hidden'
);

$form_values = array(
  'name' => '',
  'size' => '',
  'price' => '',
  'origin' => '',
  'availability' => '',
  'farmer' => '',
  'farmp' => ''
);

$sticky_values = array(
  'name' => '',
  'small' => '',
  'medium' => '',
  'large' => '',
  'price' => '',
  'origin' => '',
  'out' => '',
  'low' => '',
  'in' => '',
  'farmer' => '',
  'farmp' => ''
);

$db = open_sqlite_db('secure/site.sqlite');

if (isset($_POST['putIn'])) {

  $form_values['name'] = trim($_POST['name']);
  $form_values['size'] = (int)$_POST['size'];
  $form_values['price'] = (float)$_POST['price'];
  $form_values['origin'] = trim($_POST['origin']);
  $form_values['availability'] = (int)$_POST['availability'];
  $form_values['farmer'] = trim($_POST['farmer']);
  $form_values['farmp'] = trim($_POST['farmp']);

  $form_valid = True;


  if (!$form_values['size']) {
    $form_valid = False;
    $form_feedback_classes['size'] = '';
  }

  if ($form_values['name'] == '') {
    $form_valid = False;
    $form_feedback_classes['name'] = '';
  }

  if ($form_values['size'] == '') {
    $form_valid = False;
    $form_feedback_classes['size'] = '';
  }

  if ($form_values['price'] == '') {
    $form_valid = False;
    $form_feedback_classes['price'] = '';
  }

  if ($form_values['origin'] == '') {
    $form_valid = False;
    $form_feedback_classes['origin'] = '';
  }

  if (!$form_values['availability']) {
    $form_valid = False;
    $form_feedback_classes['availability'] = '';
  }

  if ($form_values['farmer'] == '') {
    $form_valid = False;
    $form_feedback_classes['farmer'] = '';
  }

  if ($form_values['farmp'] == '') {
    $form_valid = False;
    $form_feedback_classes['farmp'] = '';
  }

  if ($form_valid) {
    $show_confirmation = True;

    $result = exec_sql_query(
      $db,
      "INSERT INTO oysters (Oysters_Name, Size, Price, Origin, Oysters_Availability, Farmer, Farming_Practices) VALUES (:Oysters_Name, :Size, :Price, :Origin, :Oysters_Availability, :Farmer, :Farming_Practices);",
      array(
        ':Oysters_Name' => $form_values['name'],
        ':Size' => $form_values['size'],
        ':Price' => $form_values['price'],
        ':Origin' => $form_values['origin'],
        ':Oysters_Availability' => $form_values['availability'],
        ':Farmer' => $form_values['farmer'],
        ':Farming_Practices' => $form_values['farmp'],
      )
    );
  } else {
    $sticky_values['name'] = $form_values['name'];
    $sticky_values['small'] = ($form_values['size'] == 1 ? 'checked' : '');
    $sticky_values['medium'] = ($form_values['size'] == 2 ? 'checked' : '');
    $sticky_values['large'] = ($form_values['size'] == 3 ? 'checked' : '');
    $sticky_values['price'] = $form_values['price'];
    $sticky_values['origin'] = $form_values['origin'];
    $sticky_values['out'] = ($form_values['availability'] == 1 ? 'checked' : '');
    $sticky_values['low'] = ($form_values['availability'] == 2 ? 'checked' : '');
    $sticky_values['in'] = ($form_values['availability'] == 3 ? 'checked' : '');
    $sticky_values['farmer'] = $form_values['farmer'];
    $sticky_values['farmp'] = $form_values['farmp'];
  }
}

$result = exec_sql_query($db, 'SELECT * FROM oysters;');
$records = $result->fetchAll();



?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Oysters</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
</head>

<header>
  <div class="headcontent">
    <h1>Alex's Oyster Emporium</h1>

    <!-- Source: https://create.vista.com/vectors/oyster/ -->
    <img src="public/images/stock-vector-oysters-vector-engraving-style-illustration-removebg-preview-removebg-preview.png" alt="Oyster Logo">
    <cite><a href="https://create.vista.com/vectors/oyster/">Logo Retrieved From Vista.com</a></cite>
  </div>

</header>

<body>

  <?php
  if ($show_confirmation) { ?>

    <section class="confirmation">
      <h2>Your Oyster Details Have Been Collected</h2>
      <p>Please allow a couple of seconds before your submission is succesfully incorporated into our website's listings.</p>
    </section>

  <?php } ?>

  <div class="bodytag">

    <h2>Our Oysters:</h2>
    <p>Welcome to our oyster database, where oyster farmers and chefs can find a wide range of oyster varieties that will complete your catering menus! We all know you need a variety of oysters to elevate a rawbar, and here we can collaborate and maximize value within our industry. Our database is home to a vast collection of oysters, each with its own unique name, size, price, origin, availability, farmer, and farmer practices.

      Whether you're looking for large, meaty oysters to add to your signature seafood dish, or small, delicate ones to serve as a delightful appetizer, our database has got you covered. We pride ourselves on offering only the freshest, highest-quality oysters, sourced from the most reputable farmers. Enjoy!
    </p>

    <div class="table">
      <table>
        <tr>
          <th>Name</th>
          <th>Size</th>
          <th>Price</th>
          <th>Origin</th>
          <th>Availability</th>
          <th>Farmer</th>
          <th>Farming Practices</th>
        </tr>
        <?php
        foreach ($records as $choose) { ?>
          <tr>
            <td><?php echo htmlspecialchars($choose["Oysters_Name"]); ?></td>
            <td><?php echo htmlspecialchars(Largeness[$choose['Size']]); ?></td>
            <td><?php echo htmlspecialchars($choose["Price"]); ?></td>
            <td><?php echo htmlspecialchars($choose["Origin"]); ?></td>
            <td><?php echo htmlspecialchars(Available[$choose['Oysters_Availability']]); ?></td>
            <td><?php echo htmlspecialchars($choose["Farmer"]); ?></td>
            <td><?php echo htmlspecialchars($choose["Farming_Practices"]); ?></td>
          </tr>
        <?php } ?>
      </table>

      </table>

    </div>
    <!-- Source: https://littlecreekoysters.com/pages/draft-list?_pos=1&_sid=46a74cb8e&_ss=r -->
    <cite><a href="https://littlecreekoysters.com/pages/draft-list?_pos=1&_sid=46a74cb8e&_ss=r">Oysters Retrieved From Little Creek Website</a></cite>

    <div class="formy">
      <h2>Are You A Farmer?</h2>
      <p> Part of our mission at Alex's Oyster Emporium is making the oyster industry as inclusive as possible. If you are a local oyster farm in any state, and want your oysters listed, please fill out this form with the details of your product. We will collect a small listing fee of 5% on every purchase. </p>

      <form method="post" action="/" novalidate>

        <div class="former">


          <div>
            <label class="formF1" for="name">Oyster Name:</label>
            <br>

            <?php
            if ($form_feedback_classes['name'] == '') { ?>
              <p class="message">Please Give Oyster Name (i.e. Peconic Gold)</p>
            <?php } ?>

            <input value="<?php echo $sticky_values['name']; ?>" id="name" type="text" name="name" />
          </div>

          <div>
            <div class="fl">Size:</div>
            <?php
            if ($form_feedback_classes['size'] == '') { ?>
              <p class="message">Please Select A Size</p>
            <?php } ?>

            <div class="aligner">
              <div>
                <input type="radio" id="smallIn" name="size" value="1" <?php echo $sticky_values['small']; ?>>
                <label for="smallIn">Small</label>
              </div>
              <div>
                <input type="radio" id="mediumIn" name="size" value="2" <?php echo $sticky_values['medium']; ?>>
                <label for="mediumIn">Medium</label>
              </div>
              <div>
                <input type="radio" id="largeIn" name="size" value="3" <?php echo $sticky_values['large']; ?>>
                <label for="largeIn">Large</label>
              </div>
            </div>
          </div>

          <div>
            <label class="formF3" for="price">Price (50 ct Bag)*:</label>
            <br>
            <?php
            if ($form_feedback_classes['price'] == '') { ?>
              <p class="message">Please Input A Price</p>
            <?php } ?>

            <input class="price" value="<?php echo $sticky_values['price']; ?>" type="number" id="price" name="price">
            <p> * default price will be assumed to be $0 if there is no input</p>
          </div>

          <div>
            <label class="formF4" for="origin">Origin:</label>
            <br>
            <?php
            if ($form_feedback_classes['origin'] == '') { ?>
              <p class="message">Please Provide Oyster Origin (i.e. Peconic Bay, NY)</p>
            <?php } ?>

            <input value="<?php echo $sticky_values['origin']; ?>" id="origin" type="text" name="origin" />
          </div>

          <div>
            <div class="fl">Availability:</div>
            <?php
            if ($form_feedback_classes['availability'] == '') { ?>
              <p class="message">Please Select One Option</p>
            <?php } ?>
            <div class="aligner">
              <div>
                <input type="radio" id="out_avail" name="availability" value="1" <?php echo $sticky_values['out']; ?>>
                <label for="out_avail">Out of Stock</label>
              </div>
              <div>
                <input type="radio" id="low_avail" name="availability" value="2" <?php echo $sticky_values['low']; ?>>
                <label for="low_avail">Running Low</label>
              </div>
              <div>
                <input type="radio" id="in_avail" name="availability" value="3" <?php echo $sticky_values['in']; ?>>
                <label for="in_avail">In Stock</label>
              </div>
            </div>
          </div>

          <div>
            <label class="formF5" for="farmer">Farmer:</label>
            <br>
            <?php
            if ($form_feedback_classes['farmer'] == '') { ?>
              <p class="message">Please Give Oyster Farmer Name (i.e. Matt Ketcham)</p>
            <?php } ?>
            <input value="<?php echo $sticky_values['farmer']; ?>" id="farmer" type="text" name="farmer" />
          </div>


          <div>
            <label class="formF6" for="farmp">Farming Practices:</label>

            <br>
            <?php
            if ($form_feedback_classes['farmp'] == '') { ?>
              <p class="message">Please Give Description Of Growing Practices</p>
            <?php } ?>

            <textarea name="farmp" id="farmp"> <?php echo htmlspecialchars($sticky_values['farmp']); ?> </textarea>

          </div>

          <div class="form8">
            <input class="button" name="putIn" id="submit" type="submit" value="Submit Info" />
          </div>
        </div>

      </form>

    </div>


  </div>

</body>

<footer>

  <div class="cards">
    <h3>Email Us! @AlexsOysters.com</h3>
    <div class="cimg">

      <!-- Source: https://www.flaticon.com/free-icons/visa -->
      <img src="public/images/visa-2.png" alt="Visa Logo">

      <!-- Source: https://www.flaticon.com/free-icons/american-express -->
      <img src="public/images/american-express.png" alt="Amex Logo">

      <!-- Source: https://www.flaticon.com/free-icons/discover -->
      <img src="public/images/discover.png" alt="Discover Logo">

      <!-- Source: https://www.flaticon.com/free-icons/paypal -->
      <img src="public/images/paypal.png" alt="Paypal Logo">
    </div>

    <div class="citations">
      Card Icon Sources:
      <div class="cites">
        <cite><a href="https://www.flaticon.com/free-icons/visa">Visa icons created by Freepik - Flaticon</a></cite>

        <cite><a href="https://www.flaticon.com/free-icons/american-express">American express icons created by Freepik - Flaticon</a></cite>

        <cite><a href="https://www.flaticon.com/free-icons/discover">Discover icons created by Freepik - Flaticon</a></cite>

        <cite><a href="https://www.flaticon.com/free-icons/paypal">Paypal icons created by Freepik - Flaticon</a></cite>
      </div>

    </div>

    <h5> Copyright © 2023 Alex's Oyster Emporium. All Rights Reserved </h5>

  </div>


</footer>

</html>
