import { Component, OnInit } from "@angular/core";
import { environment } from "src/environments/environment";
import { Http } from "@angular/http";

@Component({
  selector: "app-root",
  templateUrl: "./app.component.html",
  styleUrls: ["./app.component.scss"]
})
export class AppComponent implements OnInit {
  title = "CalificadorExamen";
  observando = false;

  listaBdds = [];

  constructor(private http: Http) {
    this.listaBdds = environment.bdds;
    this.listaBdds.forEach(bdd => {
      bdd.estado = false;
      bdd.tablaUsuario = false;
    });
  }

  ngOnInit() {}

  observar() {
    this.observando = true;
    this.refrescar();
  }

  dejarDeObservar() {
    this.observando = false;
  }

  refrescar() {
    if (this.observando) {
      setTimeout(() => {
        this.buscarBDD();
        this.refrescar();
      }, 2000);
    }
  }
  buscarBDD() {
    this.http
      .get(environment.api + "bases")
      .toPromise()
      .then(r => {
        const respuesta = r.json();
        respuesta.forEach(element => {
          this.listaBdds.forEach(bdd => {
            if (bdd.nombre === element.Database) {
              bdd.estado = true;
              this.http
                .get(environment.api + "tablas?dataBase=" + bdd.nombre)
                .toPromise()
                .then(r2 => {
                  const respuesta2 = r2.json();
                  respuesta2.forEach(tablas => {
                    if (tablas.nombreTabla === "Usuarios") {
                      bdd.tablaUsuario = true;
                      this.http
                        .get(
                          environment.api + "usuarios?dataBase=" + bdd.nombre
                        )
                        .toPromise()
                        .then(r3 => {
                          const respuesta3 = r3.json();
                          bdd.nombrePrimerUsuario = respuesta3[0].nombre;
                          bdd.estadoPrimerUsuario = respuesta3[0].estado;
                        })
                        .catch(e => console.log(e));
                    }
                  });
                })
                .catch(e => console.log(e));
            }
          });
        });
      })
      .catch(e => console.log(e));
  }
}
