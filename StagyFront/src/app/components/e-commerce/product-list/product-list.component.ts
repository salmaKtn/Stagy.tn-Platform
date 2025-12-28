import { AsyncPipe, CommonModule, DecimalPipe } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { NgbPagination } from '@ng-bootstrap/ng-bootstrap';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { ProductListService } from '../../../shared/services/product/product-list.service';
import { AppService } from '../../../services/app.service';
import { Application } from '../../../shared/interface/Application'; 

@Component({
    selector: 'app-product-list',
    templateUrl: './product-list.component.html',
    styleUrls: ['./product-list.component.scss'],
    imports: [BreadcrumbComponent, FormsModule, CommonModule, NgbPagination, AsyncPipe],
    providers: [DecimalPipe, ProductListService]
})
export class ProductListComponent {
  companyId: number = 1; // You'll get this from route or auth later
  page = 1;
  pageSize = 10;
  searchTerm = '';

  applications: Application[] = [];
  groupedApplications: { offer_title: string; applications: (Application & { isTop3?: boolean })[] }[] = [];

  constructor(private appService: AppService) {}

  ngOnInit() {
    this.loadApplications();
  }

  loadApplications() {
    this.appService.appsByCompany(this.companyId).subscribe((data: Application[]) => {
      this.applications = data;
      data.forEach(app => {
  // Only assign random if student_compatibility is missing or invalid
  if (app.student_compatibility === undefined || app.student_compatibility === null) {
    app.student_compatibility = Math.random();
  }
  // Or if you want to forcibly override all compatibilities:
  // app.student_compatibility = Math.random();
});
      this.groupAndSortApplications();
      console.log('Grouped Applications:', this.groupedApplications);
    });
  }

  groupAndSortApplications() {
    const grouped: { [key: string]: Application[] } = {};

    // Group by offer_title
    this.applications.forEach(app => {
      if (!grouped[app.offer_title]) {
        grouped[app.offer_title] = [];
      }
      grouped[app.offer_title].push(app);
    });

    // Sort each group by compatibility and flag top 3
    this.groupedApplications = Object.keys(grouped).map(title => {
      const sortedApps = grouped[title]
        .sort((a, b) => b.student_compatibility - a.student_compatibility)
        .map((app, index) => ({
          ...app,
          isTop3: index < 3
        }));

      return {
        offer_title: title,
        applications: sortedApps
      };
    });
  }

  changeStatus(appId: number, newStatus: string) {
    this.appService.updateAppStatus(appId, newStatus).subscribe(() => {
      this.loadApplications(); // Refresh data
    });
  }
}
