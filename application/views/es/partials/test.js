var str = 'Mubashir Ahmed,"Lahore, Pakistan 54000",Lahore,5400,\'Punjab, editable\',\"test, 123 okay\"';

str = str.replace(/("(.+?)")|('(.+?)')/g, function(v){
    return v.replace(/,/g, ' ')
});


console.log(str)