import{P}from"./PageTitle-7945d5aa.js";import{S as U}from"./Success-04ed925e.js";import{U as V}from"./util-54581a61.js";import{_ as x,r as g,o as i,c as r,b as _,w as p,a as s,j as S,k as a,v as b,n as m,F as d,l as u,f as l,m as T,d as f,t as c}from"./app-5009bbee.js";const B={data(){return{msg:"",isLoading:!1,errors:{},form:{nip:"",name:"",email:"",phone:"",birthPlace:"",dateBirth:"",gender:"",position:"",address:""}}},mounted(){V.removeInvalidClass()},methods:{handleSubmit(){this.isLoading=!0,this.errors={},this.$store.dispatch("postData",["user/hub",this.form]).then(h=>{$("#successModal").modal("show"),this.msg="data berhasil ditambahkan.",this.isLoading=!1}).catch(h=>{this.isLoading=!1,this.errors=h.response.data.messages})}},components:{PageTitle:P,Success:U}},w={class:"breadcrumb m-0"},C={class:"breadcrumb-item"},N={class:"breadcrumb-item"},D=s("li",{class:"breadcrumb-item active"},"Tambah Pengguna",-1),H={class:"card"},M={class:"card-body"},A={class:"mb-2"},I=s("label",null,"NIP",-1),j=["disabled"],E={class:"mb-2"},F=s("label",null,"Nama Lengkap",-1),J=["disabled"],z={class:"mb-2"},K=s("label",null,"Email",-1),q=["disabled"],G={class:"mb-2"},O=s("label",null,"Telepon",-1),Q=["disabled"],R={class:"mb-2"},W=s("label",null,"Tempat Lahir",-1),X=["disabled"],Y={class:"mb-2"},Z=s("label",null,"Tanggal Lahir",-1),ee=["disabled"],se={class:"mb-2"},oe=s("label",null,"Jenis Kelamin",-1),ie=["disabled"],re=s("option",{value:"",selected:"",disabled:""},null,-1),ne=s("option",{value:"L"},"Laki-laki",-1),te=s("option",{value:"P"},"Perempuan",-1),le=[re,ne,te],de={class:"mb-2"},ae=s("label",null,"Jabatan",-1),me=["disabled"],ue={class:"mb-2"},ce=s("label",null,"Alamat",-1),be=["disabled"],_e={class:"card-footer border-top d-flex justify-content-between"},pe={key:0,class:"btn btn-sm btn-primary"},fe={key:1,class:"btn btn-sm btn-primary",type:"button",disabled:""},he=s("span",{class:"spinner-border spinner-border-sm me-1",role:"status","aria-hidden":"true"},null,-1);function ve(h,n,ge,ke,e,k){const v=g("router-link"),y=g("PageTitle"),L=g("Success");return i(),r(d,null,[_(y,{title:"Tambah Pengguna API Pusat"},{default:p(()=>[s("ol",w,[s("li",C,[_(v,{to:{name:"Dashboard"}},{default:p(()=>[f("Dashboard")]),_:1})]),s("li",N,[_(v,{to:{name:"User Hub"}},{default:p(()=>[f("Admin Pusat")]),_:1})]),D])]),_:1}),s("div",H,[s("form",{onSubmit:n[9]||(n[9]=S((...o)=>k.handleSubmit&&k.handleSubmit(...o),["prevent"]))},[s("div",M,[s("div",A,[I,a(s("input",{type:"number",class:m(["form-control form-validation",{"is-invalid":e.errors.nip}]),"onUpdate:modelValue":n[0]||(n[0]=o=>e.form.nip=o),disabled:e.isLoading},null,10,j),[[b,e.form.nip]]),e.errors.nip?(i(!0),r(d,{key:0},u(e.errors.nip,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",E,[F,a(s("input",{type:"text",class:m(["form-control form-validation",{"is-invalid":e.errors.name}]),"onUpdate:modelValue":n[1]||(n[1]=o=>e.form.name=o),disabled:e.isLoading},null,10,J),[[b,e.form.name]]),e.errors.name?(i(!0),r(d,{key:0},u(e.errors.name,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",z,[K,a(s("input",{type:"text",class:m(["form-control form-validation",{"is-invalid":e.errors.email}]),"onUpdate:modelValue":n[2]||(n[2]=o=>e.form.email=o),disabled:e.isLoading},null,10,q),[[b,e.form.email]]),e.errors.email?(i(!0),r(d,{key:0},u(e.errors.email,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",G,[O,a(s("input",{type:"number",class:m(["form-control form-validation",{"is-invalid":e.errors.phone}]),"onUpdate:modelValue":n[3]||(n[3]=o=>e.form.phone=o),disabled:e.isLoading},null,10,Q),[[b,e.form.phone]]),e.errors.phone?(i(!0),r(d,{key:0},u(e.errors.phone,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",R,[W,a(s("input",{type:"text",class:m(["form-control form-validation",{"is-invalid":e.errors.birthPlace}]),"onUpdate:modelValue":n[4]||(n[4]=o=>e.form.birthPlace=o),disabled:e.isLoading},null,10,X),[[b,e.form.birthPlace]]),e.errors.birthPlace?(i(!0),r(d,{key:0},u(e.errors.birthPlace,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",Y,[Z,a(s("input",{type:"date",class:m(["form-control form-validation",{"is-invalid":e.errors.dateBirth}]),"onUpdate:modelValue":n[5]||(n[5]=o=>e.form.dateBirth=o),disabled:e.isLoading},null,10,ee),[[b,e.form.dateBirth]]),e.errors.dateBirth?(i(!0),r(d,{key:0},u(e.errors.dateBirth,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",se,[oe,a(s("select",{class:m(["form-select form-validation",{"is-invalid":e.errors.gender}]),"onUpdate:modelValue":n[6]||(n[6]=o=>e.form.gender=o),disabled:e.isLoading},le,10,ie),[[T,e.form.gender]]),e.errors.gender?(i(!0),r(d,{key:0},u(e.errors.gender,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",de,[ae,a(s("input",{type:"text",class:m(["form-control form-validation",{"is-invalid":e.errors.position}]),"onUpdate:modelValue":n[7]||(n[7]=o=>e.form.position=o),disabled:e.isLoading},null,10,me),[[b,e.form.position]]),e.errors.position?(i(!0),r(d,{key:0},u(e.errors.position,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)]),s("div",ue,[ce,a(s("textarea",{class:m(["form-control form-validation",{"is-invalid":e.errors.address}]),"onUpdate:modelValue":n[8]||(n[8]=o=>e.form.address=o),disabled:e.isLoading},`\r
                    `,10,be),[[b,e.form.address]]),e.errors.address?(i(!0),r(d,{key:0},u(e.errors.address,(o,t)=>(i(),r("div",{class:"invalid-feedback",key:t},c(o)+". ",1))),128)):l("",!0)])]),s("div",_e,[_(v,{to:{name:"User Hub"},class:"btn btn-sm btn-secondary",disabled:e.isLoading},{default:p(()=>[f("Batal")]),_:1},8,["disabled"]),e.isLoading?l("",!0):(i(),r("button",pe," Simpan ")),e.isLoading?(i(),r("button",fe,[he,f(" Harap Tunggu... ")])):l("",!0)])],32)]),_(L,{url:{name:"User Hub"},msg:e.msg},null,8,["msg"])],64)}const Ve=x(B,[["render",ve]]);export{Ve as default};
