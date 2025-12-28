import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { UserService } from './user.service';

@Injectable({
  providedIn: 'root',
})
export class OfferService {
  private apiUrl = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient, private userService:UserService) {}
//all offers (works)
  getAllOffers(): Observable<any> {
    return this.http.get(`${this.apiUrl}/offer`);
  }

  //  get offers by company ID (works)
  getOffersByCompany(companyId: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/offers/company/${companyId}`);
  }
//Get offer by id 

  getOfferByID(Id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/offer/${Id}`);
  }


createOffer(offerData: any) {
    return this.http.post(`${this.apiUrl}/offer`, offerData);
  }

  

}






