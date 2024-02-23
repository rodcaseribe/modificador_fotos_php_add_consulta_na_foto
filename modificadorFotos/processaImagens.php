<head>
    <title>Processador de Imagens - Background</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <link href="https://getbootstrap.com/docs/4.1/examples/sticky-footer/sticky-footer.css" rel="stylesheet">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="environmentResizePhoto.js"></script>


    <style>
      .parent {
        position: relative;
        top: 0;
        left: 0;
      }

      .image1 {
        position: relative;
        top: 0;
        left: 0;    
        width:620px;
      }

      .image2 {
        position: absolute;
        top: 30px;
        left: 30px;  
        width:200px;
        height: 200px;
      }

    </style>
</head>

  <body>
    <div class="jjj"></div>
  </body>

<?php
  //Processador Backend
  header("Content-type: text/html; charset=utf-8"); 
  require('controllerRequest.php');
    if(!isset($_COOKIE['contador'])) {
      //captura as imagens do servidor de origem e salva no servidor atual
      $homepage = file_get_contents('http://192.168.0.19:7000/');
      preg_match_all("<a href=\x22(.+?)\x22>", $homepage, $matches);

      $json = [];
      $json2 = [];
      $json3 = [];

      foreach ($matches[0] as $value) {
        if($value !='a href="../"' && $value!='a href="Files/"' && $value!='a href="Thumbs.db"'){
            $saida = str_replace('"','',str_replace('a href="','',$value));
            
            //Checagem de cookie para nao precisar baiar as imagens
            if(!isset($_COOKIE['contador'])) {

              $image_url = 'http://192.168.0.19:7000/'.$saida;
              $save_as = 'imagensBaixadas/'.$saida;
              $ch = curl_init($image_url);
              curl_setopt($ch, CURLOPT_HEADER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
              curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US)");
              $raw_data = curl_exec($ch);
              curl_close($ch);
              $fp = fopen($save_as, 'w');
              fwrite($fp, $raw_data);
              fclose($fp);

              $recebido = requisicaoDadosIMG(str_replace(".jpg", "", $saida)); 
              if(isset($recebido['0']['saida'])){
                //echo $recebido['0']['saida'];
                array_push($json2, $recebido['0']['saida']);
                array_push($json3, $recebido['0']['desenho_st_desenho']);
              }
              else{
                array_push($json2, "");
                array_push($json3, "");
              }
          }
          $json[] = (string) $saida;
        }
      }
    }
?>


<script>
  function chamada(nomeFoto){
      var t= document.getElementsByClassName("parent")[0];
      Promise.resolve(html2canvas(t, {width: 620, height: 260}).then(

            canvas=>{
              document.body.appendChild(canvas);
              url_data=canvas.toDataURL();
              jQuery.ajax({
                url:'salvarRemoverFoto.php',
                type:'post',
                data:{
                  img:url_data,
                  nome: nomeFoto
                },
                dataType:"html",
                success:function (result) {
                  return new Promise((resolve) => {
                      resolve(canvas);
                  });
                }
              });	
              return Promise.resolve(canvas);
            }

        ));
  }


function getCookie(cName) {
      const name = cName + "=";
      const cDecoded = decodeURIComponent(document.cookie); 
      const cArr = cDecoded .split('; ');
      let res;
      cArr.forEach(val => {
          if (val.indexOf(name) === 0) res = val.substring(name.length);
      })
      return res;
}


function setCookie(cName, cValue, expDays) {
        let date = new Date();
        date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
}



    try {
      var passedArray = <?php if (isset($json)){echo json_encode($json);}else{echo json_encode("NAO GRAVAR"); } ?>;
      var passedArray2 = <?php if (isset($json2)){ echo json_encode($json2); }else{ echo json_encode("NAO GRAVAR"); } ?>;
      var passedArray3 = <?php if (isset($json3)){ echo json_encode($json3); }else{ echo json_encode("NAO GRAVAR"); } ?>;

      if(passedArray!="NAO GRAVAR"){
        //Salvando em LocalStorage
        let string = JSON.stringify(passedArray) ;
        localStorage.setItem("arrayElementos", string);
        let string2 = JSON.stringify(passedArray2) ;
        localStorage.setItem("arrayElementos2", string2);
        let string3 = JSON.stringify(passedArray3) ;
        localStorage.setItem("arrayElementos3", string3);
      }

      
    }
    catch(err) {
      console.log("caiu no catch");
    }

    //Lendo Localstorage,, se usar foreach contraolando o fluxo pelo Cookie
    let retString = localStorage.getItem("arrayElementos") ;
    let retArray = JSON.parse(retString) ;
    let retString2 = localStorage.getItem("arrayElementos2") ;
    let retArray2 = JSON.parse(retString2) ;
    let retString3 = localStorage.getItem("arrayElementos3") ;
    let retArray3 = JSON.parse(retString3) ;

    if(getCookie("contador")==null){
        document.cookie = "contador=0";
    }


    novoValor = Number(getCookie("contador"))+1;
    $('.jjj').append(`<div class="parent" id="div_id">
                        <table>
                          <tr>
                            <th style="padding-top: 98px;padding-left: 252px;font-size: 27px;">${retArray3[novoValor]}<br>
                                          ${retArray2[novoValor]}
                                            <br>
                                          ${retArray[novoValor].replace(".jpg", "")}   
                            </th>
                            <th>Savings</th>
                          </tr>
                          <tr>
                            <td><img  class="image1" src="fundoBranco.jpg" /> 
                                <img class="image2" src="/notificador/modificadorFotos/imagensBaixadas/${retArray[novoValor]}"></td>
                                                
                            <td>gsdfgsdfgsdfgsdfgsdfgsdfg</td>
                            
                          </tr>
                          <tr>
                            <td>February</td>
                            <td>$83453450</td>
                          </tr>
                        </table>
                    </div>`);


    document.cookie = "contador="+novoValor;
    chamada(retArray[novoValor]);
    setTimeout(() => {
      location.reload();
    }, 1500);
        
    document.title = "valor = "+novoValor+" de "+retArray.length;
    if(novoValor==retArray.length){
        window.location.href = "http://www.google.com.br";
        exit;
    }

</script>