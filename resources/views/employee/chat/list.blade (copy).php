@extends('clinic.layout.auth')

@section('js')
<script type="text/javascript">
  var flag=1;
    $('#angletoggle').click(function(e) {  
      //alert(1);
      if(flag == 0)
      {
        $('#main').removeClass('col-xl-8',);
        $('#main').addClass('col-xl-6',);

        $('#sidebar').removeClass('d-none');
        $('#sidebar').addClass('d-xl-block');
        
        $('#angletoggle').removeClass("active");
        flag=1;
      }
      else
      {
        $('#main').removeClass('col-xl-6');
        $('#main').addClass('col-xl-8');

        $('#sidebar').addClass('d-none');
        $('#sidebar').removeClass('d-xl-block');

        $('#angletoggle').addClass("active");
        flag = 0;
      }
      
    });
</script>
@endsection

@section('content')
	   <section class="content">
	      <div class="col-sm-12">
         
        </div>

        <div class="row">
          <div class="col-xl-4 col-lg-12 col-md-12">
             <div class="top" style="">
                <form id="custom-search-form" class="form-search form-horizontal">
                  <div class="input-append span12 chat-search">
                      <i class="fa fa-search d-none d-xs-block"></i>
                      <input type="text" class="search-query-chat" placeholder="Search">
                  </div>
                </form>
             </div>
             <div class="scroll-area">
               
                     <div class="dashboard-box-lab">
                          <a href="#">
                                <div class="row bs-example-chat">
                                    <div class="col-3"><img src="{{ asset('/images/Image_from_Skype.png') }}" width="60px" class="imagechat"/> 
                                    </div>           
                                    <div class="col-9">
                                      <span class="inner-box-lab">#jospau1234</span>
                                      <span class="inner-box-right-chat-detail">2h ago</span>
                                      <br/>
                                      <span class="black-color-class">Josh Paul</span><br/>
                                      <span class="clinic-name">Dentech Labs</span><br/>
                                      <span class="date-class">But I must explain to you how all this mistaken idea of de nouncing pleasure</span>
                                    </div>   

                                </div>
                          </a>
                      </div>
                      <div class="dashboard-box-lab">
                          <a href="#">
                                <div class="row bs-example-chat">
                                    <div class="col-3"><img src="{{ asset('/images/Image_from_Skype.png') }}" width="60px" class="imagechat"/> 
                                    </div>           
                                    <div class="col-9">
                                      <span class="inner-box-lab">#jospau1234</span>
                                      <span class="inner-box-right-chat-detail">2h ago</span>
                                      <br/>
                                      <span class="black-color-class">Josh Paul</span><br/>
                                      <span class="clinic-name">Dentech Labs</span><br/>
                                      <span class="date-class">But I must explain to you how all this mistaken idea of de nouncing pleasure</span>
                                    </div>   
                                </div>
                          </a>
                      </div>

                      <div class="dashboard-box-lab">
                          <a href="#">
                                <div class="row bs-example-chat">
                                    <div class="col-3"><img src="{{ asset('/images/Image_from_Skype.png') }}" width="60px" class="imagechat"/> 
                                    </div>           
                                    <div class="col-9">
                                      <span class="inner-box-lab">#jospau1234</span>
                                      <span class="inner-box-right-chat-detail">2h ago</span>
                                      <br/>
                                      <span class="black-color-class">Josh Paul</span><br/>
                                      <span class="clinic-name">Dentech Labs</span><br/>
                                      <span class="date-class">But I must explain to you how all this mistaken idea of de nouncing pleasure</span>
                                    </div>   
                                </div>
                          </a>
                      </div>

                      <div class="dashboard-box-lab">
                          <a href="#">
                                <div class="row bs-example-chat">
                                    <div class="col-3"><img src="{{ asset('/images/Image_from_Skype.png') }}" width="60px" class="imagechat"/> 
                                    </div>           
                                    <div class="col-9">
                                      <span class="inner-box-lab">#jospau1234</span>
                                      <span class="inner-box-right-chat-detail">2h ago</span>
                                      <br/>
                                      <span class="black-color-class">Josh Paul</span><br/>
                                      <span class="clinic-name">Dentech Labs</span><br/>
                                      <span class="date-class">But I must explain to you how all this mistaken idea of de nouncing pleasure</span>
                                    </div>   
                                </div>
                          </a>
                      </div>

                      <div class="dashboard-box-lab">
                          <a href="#">
                                <div class="row bs-example-chat">
                                    <div class="col-3"><img src="{{ asset('/images/Image_from_Skype.png') }}" width="60px" class="imagechat"/> 
                                    </div>           
                                    <div class="col-9">
                                      <span class="inner-box-lab">#jospau1234</span>
                                      <span class="inner-box-right-chat-detail">2h ago</span>
                                      <br/>
                                      <span class="black-color-class">Josh Paul</span><br/>
                                      <span class="clinic-name">Dentech Labs</span><br/>
                                      <span class="date-class">But I must explain to you how all this mistaken idea of de nouncing pleasure</span>
                                    </div>   
                                </div>
                          </a>
                      </div>
             </div>
          </div>   

          <div class="col-xl-6 col-lg-12 col-md-12 " id="main">
              <div class="dashboard-box chat-screen-heading">
                
                <div class="row bs-example-lab-detail-nobackground chat-screen">
                 <div class="col-3 pull-right">
                  <img src="{{ asset('/images/Image_from_Skype.png') }}" width="80px" class="imagechatdetail"/> 
                 </div>
                 <div class="col-9">
                  <span class="inner-box-chat-detail">#jospau1234</span><br/><br/>
                  <span class="clinic-name">Donald Marshall | Dentech Labs</span>
                  <a href="#" id="angletoggle">
                    <div>
                      <i class="fa fa-angle-right angle-control" aria-hidden="true"></i>
                    </div>
                  </a>
                 </div>
                </div>
              </div>
              <div class="box-body chat-screen">
                 <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Donald Marshall</span>
                       
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="{{ asset('/images/user.jpg') }}" alt="Message User Image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        Hey Thomas! How are you? I want some help in project.
                      </div>
                       <span class="direct-chat-timestamp pull-right">11:00 am</span>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                    <!-- Message to the right -->
                    <div class="direct-chat-msg pull-right">
                      <div class="direct-chat-text sender-chat">
                        Hello Donal Marshall,how can I help you?
                      </div>
                      <span class="direct-chat-timestamp pull-left">11:05 am</span>
                    </div>


                    <div class="direct-chat-msg receiver">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">Donald Marshall</span>
                       
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="{{ asset('/images/user.jpg') }}" alt="Message User Image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        I would like to request the revision of the design
                      </div>
                       <span class="direct-chat-timestamp pull-right">11:10 am</span>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->

                    <!-- Message to the right -->
                    <div class="direct-chat-msg pull-right">
                      <div class="direct-chat-text sender-chat">
                        Send it to what you want,I'll revision with lightning spead,but I want to pay in Rev hahaha 
                      </div>
                      <span class="direct-chat-timestamp pull-left">11:15 am</span>
                    </div>
                  </div>

              </div>

              <div class="dashboard-box-chat-detail">
                <div class="row bs-example-chat-detail">
                    <div class="col-12">
                      <button type="button" class="btn btn-lg btn-blue float-right">Delivered to Patient</button>
                      <button type="button" class="btn btn-lg btn-blue float-right">Verified by Dr.</button>
                    </div>           
                </div>
              </div>

               <div class="dashboard-box-chat-detail">
                <div class="row bs-example-chat-detail">
                    <div class="col-12">
                          <form action="#" method="post">
                            <div class="input-group">
                              <div class="image-upload">
                                <label for="file-input">
                                  <i class="fa fa-paperclip chat-paperclip" aria-hidden="true"></i>
                                </label>
                                <input id="file-input" type="file"/>
                              </div>
                              <input type="text" name="message" placeholder="Type your message here" class="form-control chat-form">
                                  <span class="">
                                    <!-- <i class="fa fa-chevron-right right-arrow-button" aria-hidden="true"></i> -->
                                    <img src="/images/send-icon.png">
                                  </span>
                            </div>
                          </form>
                    </div>           
                </div>
              </div>
          </div>

          
           <div class="col-xl-2 col-lg-2 col-md-2 d-xl-block" id="sidebar" style="display: block">
                 <img class="detail-img" src="{{ asset('/images/user.jpg') }}" alt="Message User Image"><br/>
                 <span class="color-class">Donald Marshall</span><br/>
                 <span class="clinic-name">Ceo kelabakam.com</span>
           </div>
          
        </div>
     </section>

@endsection
