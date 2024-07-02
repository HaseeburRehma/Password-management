<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <?php require 'includes/header_lib.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ENCS</title>
    <link rel="shortcut icon" href="dist/img/encs-logo.png">

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
            </div>

            <!-- Navbar -->
            <?php require 'includes/navbar.php' ?>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <?php require ('includes/left_panel.php') ?>


            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class=""><i class="nav-icon fas fa-key"></i> Password Generator</h5>
                        </div><!-- /.col -->
                        <div class="col-md-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Passsword Generator</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content mt-3">
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-md-3">

                            <div class="card card-info text-center font-weight-bold">
                                <div class=" bg-info p-2  text-center font-weight-bold">
                                    <span class="text-white  text-center font-size-3 size">Complexity</span>
                                </div>
                                <div class="card-body"
                                    style="background: url('includes/images/side.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-6">Upper Letters</div>
                                                <div class="col-6">
                                                    <!-- Default checked -->
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="uppercase" checked disabled>
                                                        <label class="custom-control-label" for="uppercase">on</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-6">Lower Letters</div>
                                                <div class="col-6">
                                                    <!-- <div class="form-check"> -->
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="lowercase" checked disabled>
                                                        <label class="custom-control-label" for="lowercase">on</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">Numbers</div>
                                                <div class="col-12 col-sm-6">
                                                    <!-- <div class="form-check"> -->
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="number_id" checked disabled>
                                                        <label class="custom-control-label" for="number_id">on</label>
                                                    </div>
                                                    <!-- <input class="form-check-input" type="checkbox" value="" id="number_id">
                                    </div> -->
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">Special Characters</div>
                                                <div class="col-12 col-sm-6">
                                                    <!-- <div class="form-check"> -->
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" id="char_id"
                                                            checked disabled>
                                                        <label class="custom-control-label" for="char_id">on</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">Length</div>
                                                <div class="col-12 col-sm-6">

                                                    <div class="form-group">

                                                        <input type="number" class="form-control" value="16" id="lenght"
                                                            name="lenght" placeholder="Lenght">
                                                    </div>
                                                </div>

                                            </div>
                                        </li>

                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">No of Passwords</div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" value="15"
                                                            id="passwords" placeholder="passwords">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="card-footer text-muted text-center">
                                        <button id="redirect" type="button" class="btn btn-success"
                                            onclick="makeid()">Click to Generate Number</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
              </div> -->
                        <div class="col-md-8">

                            <!--  <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Results</h3>
              </div>
              <div class="card-body">
                
                <div class="row">
                  <div class="col-12">
                    Results Goes here
                  </div>
                </div>
                
              </div> -->

                            <!-- /.card-body -->
                            <!-- </div> -->

                            <div id="output">
                                <div class=" bg-success p-2  text-center font-weight-bold">
                                    <span class="text-white  text-center font-size-3 size">Results</span>
                                </div>
                                <!-- <div id='save_col_div' class="" style="padding:15px;display: none;">
                   <form method="POST">
                    <div style="display: flex;">
                        <input type="hidden" name="passwords" id="passes">
                        <input type="text" name="description" placeholder="Description" class="form-control" style="width: 30%">
                        <select name="client_name" id="" class="form-control" style="width: 50%"> -->
                                <!-- php 
                                $query = mysqli_query($con,"select * from clients");
                                while ($row = mysqli_fetch_array($query)) {
                                  echo "<option value='".$row['id']."'>".$row['client_name']."</option>";
                                }
                             ?> -->
                                </select>
                                <!-- <button type="submit" name="save_collection" class="btn btn-success " id="save-col">Save Collection</button>
                      </div> -->
                                </form>
                            </div>
                            <div style="background-color:white;" class="mb-5" id="output-final"></div>
                        </div>

                    </div>

                </div>


        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->

        </div>
        <!-- ./wrapper -->
        <script src='paswordgenerator.js'> </script>
        <?php require ('includes/footer.php') ?>
        <?php require ('includes/footer_lib.php') ?>

    </body>

</html>