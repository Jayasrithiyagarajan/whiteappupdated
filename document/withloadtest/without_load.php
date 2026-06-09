<?php
include_once('../../inc/function.php');

?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white ">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">WITHOUT LOAD TEST</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn">View List</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <!-- Base Horizontal Form -->
                <div class="form-element py-30 mb-30">
                    <h4 class="font-20 mb-30">Header Data</h4>
                    <!-- Form -->
                    <form action="#" method="POST">


                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Thorough Examination</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" placeholder="Date of Thorough Examination">
                            </div>
                        </div>
                        <!-- End Form Row -->


                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Report</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" placeholder="Date of Report">
                            </div>
                        </div>
                        <!-- End Form Row -->


                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Report No">
                            </div>
                        </div>
                        <!-- End Form Row -->

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Sticker No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Sticker No">
                            </div>
                        </div>


                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form -->
            </div>
            <div class="col-lg-6">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 mb-30">
                    <h4 class="font-20 mb-30">Customer Information / Inspector </h4>

                    <!-- Form -->
                    <form action="#" method="POST">

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Name</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <img src="../../assets/img/svg/user3.svg" alt="" class="svg">
                                        </div>
                                    </div>

                                    <input type="text" class="form-control pl-1" placeholder="Type Your Name">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Row -->

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <img src="../../assets/img/svg/mail3.svg" alt="" class="svg">
                                        </div>
                                    </div>
                                    <input type="email" class="form-control pl-1" placeholder="Type Email Address">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Row -->

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <img src="../../assets/img/svg/mobile3.svg" alt="" class="svg">
                                        </div>
                                    </div>
                                    <input type="number" class="form-control pl-1" placeholder="Contact Number">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Row -->

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <img src="../../assets/img/svg/key3.svg" alt="" class="svg">
                                        </div>
                                    </div>
                                    <input type="text" class="form-control pl-1" placeholder="Inspector name">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Row -->

                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>


            <!-- <body part> -->



            <div class="col-lg-6">
                <!-- Base Horizontal Form -->
                <div class="form-element py-30 mb-30">
                    <!-- <h4 class="font-20 mb-30">Header Data</h4> -->
                    <!-- Form -->
                    <form action="#" method="POST">


                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Name and Address of employer for whom the thorough examination was made:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" placeholder="Date of Thorough Examination">
                            </div>
                        </div>
                        <!-- End Form Row -->
                        <!-- <h4 class="font-20 mb-30">Customer Information / Inspector </h4> -->

                        <!-- Form Row -->
                        <!-- <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Date of Report</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="date" class="theme-input-style" placeholder="Date of Report">
                                        </div>
                                    </div> -->
                        <!-- End Form Row -->


                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Description and Identification of the equipment:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Description and Identification of the equipment:">
                            </div>
                        </div>
                        <!-- End Form Row -->





                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Manufacturer</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Manufacturer">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Model:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Model:">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Equipment ID No.: </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Equipment ID No">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Equipment Serial No.:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Equipment Serial No">
                            </div>
                        </div>






                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold"> Certificate No.:
                                </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Enter Certificate No">
                            </div>
                        </div>


                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold"> JRN:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Enter JRN">
                            </div>
                        </div>


                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form -->
            </div>




            <div class="col-lg-6">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 mb-30" style="height: 638px;">
                    <!-- <h4 class="font-20 mb-30">Customer Information / Inspector </h4> -->

                    <!-- Form -->
                    <form action="#" method="POST">

                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Address of premises at which the examination was made:</label>
                            </div>


                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <img src="../../assets/img/svg/user3.svg" alt="" class="svg">
                                        </div>
                                    </div>

                                    <input type="text" class="form-control pl-1" placeholder="Type Address of premises">
                                </div>
                            </div>
                        </div>
                        <!-- End Form Row -->









                        <!-- Form Row -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold"> Safe Working Load:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" placeholder="Enter Safe Working Load">
                            </div>
                        </div>

                        <!-- End Form Row -->

                        <!-- Additional Row 1 -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Manufacture (if known):</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style">
                            </div>
                        </div>
                        <!-- End Additional Row 1 -->

                        <!-- Additional Row 2 -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Last Thorough Examination:</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style">
                            </div>
                        </div>
                        <!-- End Additional Row 2 -->
                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>


            <!-- end -->









            <div class="col-lg-6">
                <!-- Base Horizontal Form -->
                <div class="form-element py-30 mb-30" style="height: 367px;">
                    <!-- <h4 class="font-20 mb-30">Header Data</h4> -->
                    <!-- Form -->
                    <form action="#" method="POST">


                        <!-- Form Row -->
                        <!-- Additional Input Row 3 (First Examination) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">Is this the first examination after installation or assembly at a new site or location?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="first_examination" value="yes"> YES</label>
                                <label><input type="radio" name="first_examination" value="no"> NO</label>
                            </div>
                        </div>

                        <!-- Additional Input Row 4 (Equipment Installation) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">If the answer to the above question is YES, has the equipment been installed correctly?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="installed_correctly" value="yes"> YES</label>
                                <label><input type="radio" name="installed_correctly" value="no"> NO</label>
                            </div>
                        </div>
                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form -->
            </div>




            <div class="col-lg-6">
                <!-- Base Horizontal Form With Icons -->
                <div class="form-element py-30 mb-30">
                    <h4 class="font-20 mb-30">Was the examination carried out:</h4>

                    <!-- Form -->
                    <form action="#" method="POST">

                        <!-- Form Row -->
                        <!-- End Form Row -->





                        <!-- Additional Input Row 5 (Interval of 6 months) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">Within an interval of 6 months?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="interval_6_months" value="yes"> YES</label>
                                <label><input type="radio" name="interval_6_months" value="no"> NO</label>
                            </div>
                        </div>

                        <!-- Additional Input Row 6 (Interval of 12 months) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">Within an interval of 12 months?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="interval_12_months" value="yes"> YES</label>
                                <label><input type="radio" name="interval_12_months" value="no"> NO</label>
                            </div>
                        </div>

                        <!-- Additional Input Row 7 (Examination Scheme) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">In accordance with an examination scheme?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="examination_scheme" value="yes"> YES</label>
                                <label><input type="radio" name="examination_scheme" value="no"> NO</label>
                            </div>
                        </div>

                        <!-- Additional Input Row 8 (Exceptional Circumstances) -->
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold">After the occurrence of exceptional circumstances?</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="exceptional_circumstances" value="yes"> YES</label>
                                <label><input type="radio" name="exceptional_circumstances" value="no"> NO</label>
                            </div>
                        </div>





                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>
                <!-- End Horizontal Form With Icons -->
            </div>


            <!-- end -->


















            <div class="col-lg-12">
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">A. GENERAL INFORMATION</h4>
                    <!-- Form -->
                    <form action="#" method="POST">
                        <div class="row">
                            <div class="col-lg-6">


                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect (If none state NONE)</label>
                                    <textarea class="theme-input-style" placeholder="Enter details"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Is the above a defect which is of immediate danger to persons</label>
                                    <select class="theme-input-style">
                                        <option value="">Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Is the above a defect which is not yet but could become a danger to persons: (If YES state the date by when)</label>
                                    <input type="date" class="theme-input-style">
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Particulars of any repair, renewal or alteration required to remedy the defect identified above:</label>
                                    <textarea class="theme-input-style" placeholder="Enter details"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Particulars of any tests carried out as part of the examination: (If none state NONE) (SEE ATTACHED PAGE 2)</label>
                                    <textarea class="theme-input-style" placeholder="Enter details"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">IS THIS EQUIPMENT FIT FOR PURPOSE?</label>
                                    <select class="theme-input-style">
                                        <option value="">Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>


                            </div>

                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Name & Qualifications of person making this report:</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter name & qualifications">
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Name of person authenticating this report:</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter name">
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Latest date by which next thorough examination must be carried out:</label>
                                    <input type="date" class="theme-input-style">
                                </div>

                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Name and address of employer of persons making and authenticating this report:</label>
                                    <textarea class="theme-input-style" placeholder="Enter name and address"></textarea>
                                </div>

                            </div>
                        </div>



                        <!-- Form Row -->
                        <div class="form-row">
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn long">Save</button>
                            </div>
                        </div>
                        <!-- End Form Row -->
                    </form>
                    <!-- End Form -->
                </div>

            </div>











        </div>

    </div>
</div>
</div>
<!-- End Main Content -->

<?php
include_once('../../inc/footer.php');
?>