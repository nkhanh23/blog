                     <div class="col-lg-5 col-xl-4">
                         <div class="bg-light rounded p-4 pt-0">
                             <div class="row g-4">
                                 <div class="col-12">
                                     <div class="rounded overflow-hidden">
                                         <img src="<?php echo $getAllPosts[0]['thumbnail'] ?>"
                                             class="img-fluid rounded img-zoomin w-100" alt="">
                                     </div>
                                 </div>
                                 <?php foreach ($getPostsLimit7 as $item): ?>
                                     <div class="col-12">
                                         <div class="d-flex flex-column">
                                             <a href="#" class="h4 mb-2"><?php echo $item['tittle'] ?></a>
                                             <p class="fs-5 mb-0"><i class="fa fa-clock"><?php echo $item['minute_reads'] ?>
                                                     minute read</i> </p>
                                             <p class="fs-5 mb-0"><i class="fa fa-eye">
                                                     <?php echo $item['views'] ?>k Views</i></p>
                                         </div>
                                     </div>
                                 <?php endforeach; ?>
                             </div>
                         </div>
                     </div>