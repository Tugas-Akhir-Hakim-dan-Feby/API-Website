import{P as _}from"./PageTitle-78e8a2ae.js";import{S as b}from"./Success-8789b882.js";import{_ as h,r as s,o as a,c as n,a as e,b as o,j as p,F as i,l as f,w as g,t as v,d as y}from"./app-4e61fff3.js";const N={data(){return{msg:"",branches:[{id:1,branchName:"Cabang 1",branchAddress:"Jl. Raya No. 1",branchPhone:"081234567890"},{id:2,branchName:"Cabang 2",branchAddress:"Jl. Raya No. 2",branchPhone:"081234567890"},{id:3,branchName:"Cabang 3",branchAddress:"Jl. Raya No. 3",branchPhone:"081234567890"}]}},methods:{handleSubmit(){$("#successModal").modal("show"),this.msg="data berhasil disimpan."}},components:{PageTitle:_,Success:b}},P={class:"container-fluid"},S={class:"card"},x={class:"card-body"},C=e("div",{class:"mb-2"},[e("label",null,"Nama Lengkap"),e("input",{type:"text",class:"form-control",value:"John Doe"})],-1),k=e("div",{class:"mb-2"},[e("label",null,"Email"),e("input",{type:"text",class:"form-control",value:"john.doe@mailinator.com"})],-1),B={class:"mb-2"},w=e("label",null,"Cabang",-1),J={name:"",id:"",class:"form-control"},T=e("option",{value:""},"Pilih Cabang",-1),A=["value","selected"],E=e("div",{class:"mb-2"},[e("label",null,"Jabatan"),e("input",{type:"text",class:"form-control",value:"Sekretaris"})],-1),j=e("div",{class:"mb-2"},[e("label",null,"Telepon"),e("input",{type:"text",class:"form-control",value:"081234567890"})],-1),R={class:"card-footer border-top d-flex justify-content-between"},V=e("button",{class:"btn btn-primary"},"Simpan",-1);function D(F,l,L,M,c,r){const d=s("PageTitle"),m=s("router-link"),u=s("Success");return a(),n(i,null,[e("div",P,[o(d,{title:"Edit Pengguna API Pusat"}),e("div",S,[e("form",{onSubmit:l[0]||(l[0]=p((...t)=>r.handleSubmit&&r.handleSubmit(...t),["prevent"]))},[e("div",x,[C,k,e("div",B,[w,e("select",J,[T,(a(!0),n(i,null,f(c.branches,t=>(a(),n("option",{value:t.id,selected:t.id==1},v(t.branchName),9,A))),256))])]),E,j]),e("div",R,[o(m,{to:{name:"User Branch"},class:"btn btn-secondary"},{default:g(()=>[y("Batal")]),_:1}),V])],32)])]),o(u,{url:{name:"User Branch"},msg:c.msg},null,8,["msg"])],64)}const z=h(N,[["render",D]]);export{z as default};