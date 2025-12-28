import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Application } from '../shared/interface/Application';

@Injectable({
  providedIn: 'root',
})
export class AppService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient) {}

/**     ***************       ****************    ALL ABOUT APPLICATIONS     **************         ***************** */
apply(appData: any)
{
      return this.http.post(`${this.apiUrl}/application`, appData);
}


 appsByCompany(companyId: number): Observable<Application[]> {
  return this.http.get<Application[]>(`${this.apiUrl}/application/company/${companyId}`);
}


updateAppStatus(id: number, status: string) {
  return this.http.put(`http://127.0.0.1:8000/api/application/${id}/status`, { status });
}


}


