import{P as u}from"./PageTitle-7945d5aa.js";import{S as p}from"./Success-04ed925e.js";import{_ as h,r as s,o as n,c as o,a as e,b as a,j as b,F as r,l as g,w as f,t as v,d as k}from"./app-5009bbee.js";const x={data(){return{msg:"",skills:[{id:1,skillName:"Welding Engineer"},{id:2,skillName:"Welding Inspector"},{id:3,skillName:"Welding Specialist"},{id:4,skillName:"Welding Destructive Testing"},{id:5,skillName:"Welding Non-Destructive Testing"}]}},methods:{handleSubmit(){$("#successModal").modal("show"),this.msg="data berhasil ditambahkan."}},components:{PageTitle:u,Success:p}},N={class:"container-fluid"},S={class:"card"},y={class:"card-body"},T=e("div",{class:"mb-2"},[e("label",null,"Nama Lengkap"),e("input",{type:"text",class:"form-control"})],-1),P=e("div",{class:"mb-2"},[e("label",null,"Email"),e("input",{type:"text",class:"form-control"})],-1),w={class:"mb-2"},E=e("label",null,"Keahlian",-1),W={name:"",id:"",class:"form-control"},B=e("option",{value:""},"Pilih Keahlian",-1),C=["value"],D=e("div",{class:"mb-2"},[e("label",null,"Telepon"),e("input",{type:"text",class:"form-control"})],-1),V={class:"card-footer border-top d-flex justify-content-between"},j=e("button",{class:"btn btn-primary"},"Simpan",-1);function F(K,l,L,M,i,c){const d=s("PageTitle"),m=s("router-link"),_=s("Success");return n(),o(r,null,[e("div",N,[a(d,{title:"Tambah Pengguna Pakar"}),e("div",S,[e("form",{onSubmit:l[0]||(l[0]=b((...t)=>c.handleSubmit&&c.handleSubmit(...t),["prevent"]))},[e("div",y,[T,P,e("div",w,[E,e("select",W,[B,(n(!0),o(r,null,g(i.skills,t=>(n(),o("option",{value:t.id},v(t.skillName),9,C))),256))])]),D]),e("div",V,[a(m,{to:{name:"User Expert"},class:"btn btn-secondary"},{default:f(()=>[k("Batal")]),_:1}),j])],32)])]),a(_,{url:{name:"User Expert"},msg:i.msg},null,8,["msg"])],64)}const z=h(x,[["render",F]]);export{z as default};
