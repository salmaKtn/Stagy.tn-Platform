import { CommonModule,  } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { Project } from '../../../shared/interface/product-list';
import { UserService } from '../../../services/user.service';
@Component({
    selector: 'app-project-list',
    templateUrl: './project-list.component.html',
    styleUrls: ['./project-list.component.scss'],
    imports: [BreadcrumbComponent, CommonModule, RouterLink,]
})
export class ProjectListComponent implements OnInit {
  active = 1
  public openTab : string = "All";
  
  // Categories you want to filter on
  categories: string[] = [
    'All',
    'PFE',
    'Découverte',
    'ouvrier',
    'été',
    'Pré-embauche',
    
  ];
  // All offers retrieved from backend
  public offers: any[] = [];

  // Filtered offers (displayed in template)
  public filterData: any[] = [];
    
  
  constructor(private userService: UserService) {}

  ngOnInit(): void {

    this.userService.getOffersByCompany().subscribe({
      next: (res) => {
        this.offers = res;
        this.filterData = this.offers; // show all by default
            console.log('filterData', this.filterData);  // ✅ Now inside
            

      },
      error: (err) => {
        console.error('Error loading offers:', err);
      }
    });

  }
// Called when a tab (category) is clicked
  public tabbed(val: string): void {
    this.openTab = val;
    this.filterData =
      val !== 'All'
        ? this.offers.filter((offer: any) => offer.category === val)
        : this.offers;
      
  }

}






  


