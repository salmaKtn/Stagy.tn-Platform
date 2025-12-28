import { Component, OnInit } from '@angular/core';
import { BreadcrumbComponent } from '../../../shared/components/breadcrumb/breadcrumb.component';
import { BarChartComponent } from './bar-chart/bar-chart.component';

import { DonutChartComponent } from './donut-chart/donut-chart.component';
import { MixedChartComponent } from './mixed-chart/mixed-chart.component';
import { PieChartComponent } from './pie-chart/pie-chart.component';
import { RadarChartComponent } from './radar-chart/radar-chart.component';

@Component({
    selector: 'app-apex-chart',
    templateUrl: './apex-chart.component.html',
    styleUrls: ['./apex-chart.component.scss'],
    imports: [BreadcrumbComponent, 
        BarChartComponent,
         PieChartComponent, DonutChartComponent,
        MixedChartComponent, RadarChartComponent]
})
export class ApexChartComponent implements OnInit {


  constructor() { }

  ngOnInit(): void {
  }

}
