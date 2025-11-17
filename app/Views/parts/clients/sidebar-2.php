                        <div class="col-lg-4 col-xl-3">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="p-3 rounded border">
                                        <h4 class="mb-4">Stay Connected</h4>
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <a href="#"
                                                    class="w-100 rounded btn btn-primary d-flex align-items-center p-3 mb-2">
                                                    <i
                                                        class="fab fa-facebook-f btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">13,977 Fans</span>
                                                </a>
                                                <a href="#"
                                                    class="w-100 rounded btn btn-danger d-flex align-items-center p-3 mb-2">
                                                    <i
                                                        class="fab fa-twitter btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">21,798 Follower</span>
                                                </a>
                                                <a href="#"
                                                    class="w-100 rounded btn btn-warning d-flex align-items-center p-3 mb-2">
                                                    <i
                                                        class="fab fa-youtube btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">7,999 Subscriber</span>
                                                </a>
                                                <a href="#"
                                                    class="w-100 rounded btn btn-dark d-flex align-items-center p-3 mb-2">
                                                    <i
                                                        class="fab fa-instagram btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">19,764 Follower</span>
                                                </a>
                                                <a href="#"
                                                    class="w-100 rounded btn btn-secondary d-flex align-items-center p-3 mb-2">
                                                    <i
                                                        class="bi-cloud btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">31,999 Subscriber</span>
                                                </a>
                                                <a href="#"
                                                    class="w-100 rounded btn btn-warning d-flex align-items-center p-3 mb-4">
                                                    <i
                                                        class="fab fa-dribbble btn btn-light btn-square rounded-circle me-3"></i>
                                                    <span class="text-white">37,999 Subscriber</span>
                                                </a>
                                            </div>
                                        </div>
                                        <h4 class="my-4">Popular News</h4>
                                        <div class="row g-4">
                                            <?php foreach($getTopPostViewLimit as $item): ?>
                                            <div class="col-12">
                                                <div class="row g-4 align-items-center features-item">
                                                    <div class="col-4">
                                                        <div class="rounded-circle position-relative">
                                                            <div class="overflow-hidden rounded-circle">
                                                                <img src="<?php echo $item['thumbnail'] ?>"
                                                                    class="img-zoomin img-fluid rounded-circle w-100"
                                                                    alt="">
                                                            </div>
                                                            <span
                                                                class="rounded-circle border border-2 border-white bg-primary btn-sm-square text-white position-absolute"
                                                                style="top: 10%; right: -10px;">3</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="features-content d-flex flex-column">
                                                            <p class="text-uppercase mb-2">Sports</p>
                                                            <a href="#" class="h6">
                                                                <?php echo $item['tittle'] ?>
                                                            </a>
                                                            <small class="text-body d-block"><i
                                                                    class="fas fa-calendar-alt me-1"></i>
                                                                <?php echo $item['created_at'] ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>