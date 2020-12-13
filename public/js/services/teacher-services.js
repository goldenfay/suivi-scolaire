function fetchRows(url){
    return $.ajax({
        url: url,
        type: 'GET',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest',
        },
        success: res=>{
        
         
          return Promise.resolve(res) ;
  
        },
        error: err=>{
            return Promise.reject(err) ;
          
        }
  
  
      })


}