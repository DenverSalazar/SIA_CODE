<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card 2 Details</title>
    <link rel="stylesheet" href="/SIA/css/bootstrap.min.css">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<style>
    body{
        background-image: url(img/dotabg.jpg);
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>

<body>
    <?php
        $card4 = array("cImg" => "<img src='/SIA/img/libro.jpg' style='height: 100%;'>", 
                "cTitle" => "Earth Spirit",
                "cDesc" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum quo dolores reiciendis consectetur iste velit nemo aliquam voluptatum nobis, consequuntur blanditiis, distinctio sint repellendus commodi minima sequi, atque quod nam?
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum quo dolores reiciendis consectetur iste velit nemo aliquam voluptatum nobis, consequuntur blanditiis, distinctio sint repellendus commodi minima sequi, atque quod nam?",
                "cPrice" => "$1,700",
                "cRole" => "Role: Support",
                "cCombo" => "Abilities: Bolder Smash | Rolling Boulder | Geomagnetic Grip | Magnetize",
                "cSpecs" => "Lorem ipsum dolor sit amet consectetur <br> adipisicing elit. Eum quo dolores reiciendis <br> consectetur iste velit nemo aliquam voluptatum <br> nobis, consequuntur blanditiis, distinctio sint <br> repellendus commodi minima sequi, atque quod nam?");
    ?>
    
    <section>
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3 h-100" style="background: rgba(255, 251, 251, 0.5);">
                        <?php
                            foreach($card4 as $key => $value){
                                if($key == "cImg"){
                                    echo $value;
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="col-lg-7 mt-5">
                    <div class="card p-4" style="background: rgba(255, 251, 251, 0.5);">
                        <div class="card-body p-0">
                            <!-- Title and Price -->
                            <?php
                                foreach($card4 as $key => $value){
                                    if($key == "cTitle"){
                                        echo "<h1>" . $value . "</h1>";
                                    }
                                    elseif($key == "cPrice"){ 
                                        echo "<p class='h3 py-2'>" . $value . "</p></div>";
                                    } 
                                }
                                ?>
                            <!-- Stars -->
                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <span class="text-dark">Rating 5.0 | 101 Comments</span>
                            </p>
                            <!-- Roles -->
                            <?php
                                foreach($card4 as $key => $value){
                                    if($key == "cRole"){
                                        echo "<h6 class='text-muted'><b>" . $value . "</b></h6><br>";
                                    }
                                }
                            ?>
                            <!-- Description -->
                            <h6>Description:</h6>
                            <?php
                                foreach($card4 as $key => $value){
                                    if($key == "cDesc"){
                                        echo "<p>" . $value . "</p>";
                                    }
                                }
                            ?>
                            <!-- Combo/Abilites -->
                            <?php
                                foreach($card4 as $key => $value){
                                    if($key == "cCombo"){
                                        echo "<h6 class='text-muted'><b>" . $value . "</b></h6><br>";
                                    }
                                }
                            ?>
                            <!-- Specs -->
                            <h6>Specification:</h6>
                            <?php
                                foreach($card4 as $key => $value){
                                    if($key == "cSpecs"){
                                        echo "<p>" . $value . "</p>";
                                    }
                                }
                            ?>
                            
                            <div class="btn-group">
                                <button class="btn btn-primary me-2 w-40" style="border-radius: 0.3875rem;" onclick="window.location.href = 'index.php'">Buy</button>
                                <button class="btn btn-primary w-40" style="border-radius: 0.3875rem;">Add to Cart</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   


   

    <script src="/SIA/js/bootstrap.bundle.min.js"></script>
</body>
</html>