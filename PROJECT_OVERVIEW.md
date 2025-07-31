# ICT for Persons with Disabilities (PWDS) - Backend System

## Project Overview

The ICT for Persons with Disabilities platform is a comprehensive digital ecosystem designed to enhance the lives of persons with disabilities in Uganda. This Laravel-based backend system serves as the foundation for a multi-platform solution that includes web and mobile applications.

## Mission Statement

To enhance Knowledge Management, ICT Adoption, Digital Skills, and Access to E-Services for Persons with Disabilities in Uganda through a collaborative platform that connects PWDs with opportunities, services, and support systems.

## Key Stakeholders

- **Uganda Communications Commission (UCC)**: Primary sponsor through UCUSAF
- **National Union of Disabled Persons of Uganda (NUDIPU)**: Implementation partner
- **8 Technologies Consultants**: Technical development partner
- **Persons with Disabilities**: Primary beneficiaries
- **Service Providers**: Organizations offering services to PWDs
- **Caregivers & Well-wishers**: Support network

## Core Features

### 1. Person with Disabilities Profiling
- **Purpose**: Register PWDs to the Uganda National Database
- **Features**:
  - Comprehensive personal information capture
  - Disability type classification
  - Geographic location tracking
  - Identity verification system
  - Academic qualifications management
  - Employment history tracking

### 2. Jobs and Opportunities
- **Purpose**: Connect PWDs with suitable employment opportunities
- **Features**:
  - Job posting and application system
  - Skills-based job matching
  - Employment status tracking
  - Employer-PWD connection platform

### 3. Service Providers Directory
- **Purpose**: Catalog organizations serving PWDs
- **Features**:
  - Service provider registration
  - Service categorization
  - Contact information management
  - Service quality tracking

### 4. E-Commerce Platform (Shop)
- **Purpose**: Marketplace for disability-related products and services
- **Features**:
  - Product catalog management
  - Order processing system
  - Vendor management
  - Payment integration

### 5. Counseling Services
- **Purpose**: Mental health and psychosocial support
- **Features**:
  - Counselor directory
  - Service provider mapping
  - Appointment scheduling
  - Geographic coverage tracking

### 6. News & Information System
- **Purpose**: Keep community informed about disability-related news
- **Features**:
  - News article management
  - Category-based organization
  - Content publishing workflow
  - Community engagement features

### 7. Events Management
- **Purpose**: Organize and promote disability-related events
- **Features**:
  - Event creation and management
  - Registration system
  - Speaker management
  - Ticketing system

### 8. Associations & Organizations
- **Purpose**: Connect PWDs with relevant organizations
- **Features**:
  - Association directory
  - Membership management
  - Organization profiling
  - Contact management

### 9. Innovations Showcase
- **Purpose**: Highlight innovative solutions for PWDs
- **Features**:
  - Innovation catalog
  - Success story documentation
  - Technology showcasing
  - Community recognition

## Technical Architecture

### Backend Technology Stack
- **Framework**: Laravel 8.x
- **Language**: PHP 7.3+
- **Database**: MySQL/MariaDB
- **Authentication**: JWT (JSON Web Tokens)
- **Admin Panel**: Laravel Admin (Encore)
- **API**: RESTful API architecture
- **File Storage**: Local storage with web access
- **PDF Generation**: DomPDF
- **Excel Processing**: Maatwebsite Excel

### Key Laravel Packages
- `encore/laravel-admin`: Administrative interface
- `tymon/jwt-auth`: JWT authentication
- `barryvdh/laravel-dompdf`: PDF generation
- `maatwebsite/excel`: Excel import/export
- `livewire/livewire`: Dynamic frontend components
- `propaganistas/laravel-phone`: Phone number validation
- `laravel-admin-ext/chartjs`: Data visualization

### Database Structure

#### Core Tables
- **people**: Main PWD registry
- **disabilities**: Disability types and classifications
- **districts**: Geographic administrative divisions
- **organisations**: District unions and organizations
- **service_providers**: Service provider registry
- **jobs**: Job opportunities
- **job_applications**: Application tracking
- **products**: E-commerce products
- **news_posts**: News and articles
- **events**: Event management
- **counselling_centres**: Counseling service providers
- **associations**: PWD associations
- **innovations**: Innovation showcase

#### Supporting Tables
- **academic_qualifications**: Educational background
- **employment_history**: Work experience
- **next_of_kins**: Emergency contacts
- **memberships**: Organization memberships
- **testimonies**: Success stories
- **resources**: Educational resources

## API Endpoints

### Authentication
- `POST /api/users/register` - User registration
- `POST /api/users/login` - User login
- `POST /api/logout` - User logout

### Core Resources
- `GET|POST /api/people` - PWD management
- `GET|POST /api/district-unions` - Organization management
- `GET|POST /api/service-providers` - Service provider management
- `GET|POST /api/jobs` - Job opportunities
- `GET|POST /api/news-posts` - News management
- `GET|POST /api/events` - Event management
- `GET|POST /api/counselling-centres` - Counseling services
- `GET|POST /api/associations` - Association management
- `GET|POST /api/products` - E-commerce products
- `GET|POST /api/innovations` - Innovation showcase

### Utility Endpoints
- `GET /api/disabilities` - Disability types
- `GET /api/districts` - Geographic data

## Security Features

### Authentication & Authorization
- JWT-based authentication
- Role-based access control
- Admin panel security
- API route protection

### Data Protection
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection

### Profile Verification
- Manual profile review process
- Identity verification workflow
- Admin approval system

## Development Workflow

### Local Development Setup
```bash
# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Key Artisan Commands
- `php artisan migrate` - Run database migrations
- `php artisan admin:make` - Create admin controllers
- `php artisan make:model` - Create Eloquent models
- `php artisan make:controller` - Create API controllers

## Integration Points

### Mobile Application
- RESTful API endpoints for mobile consumption
- JWT token-based authentication
- Synchronized data between web and mobile platforms

### USSD Integration
- USSD gateway for feature phone access
- Basic registration and information services
- Accessibility for users without smartphones

### Third-Party Services
- Email services for notifications
- SMS services for alerts
- Payment gateway integration
- Geographic data services

## Deployment & Infrastructure

### Server Requirements
- PHP 7.3 or higher
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- SSL certificate for HTTPS

### Production Considerations
- Database optimization
- File storage management
- Backup and recovery procedures
- Performance monitoring
- Security updates

## Data Analytics & Reporting

### Key Metrics
- PWD registration statistics
- Geographic distribution analysis
- Service utilization rates
- Employment placement success
- Platform engagement metrics

### Report Generation
- PDF report exports
- Excel data exports
- Dashboard visualizations
- Administrative analytics

## Future Enhancements

### Planned Features
- Advanced matching algorithms
- AI-powered service recommendations
- Integration with government databases
- Enhanced mobile accessibility features
- Real-time chat and communication

### Scalability Considerations
- Microservices architecture migration
- Cloud infrastructure adoption
- API performance optimization
- Database sharding strategies

## Contributing

### Development Standards
- Follow Laravel coding conventions
- Implement proper error handling
- Write comprehensive tests
- Document API changes
- Follow security best practices

### Code Organization
- Model-Controller separation
- Service layer implementation
- Repository pattern adoption
- Event-driven architecture

## Support & Maintenance

### Technical Support
- Regular security updates
- Performance monitoring
- Bug fixes and patches
- Feature enhancements

### Community Support
- User training programs
- Documentation updates
- Feedback collection
- Continuous improvement

## Contact Information

- **Project Email**: info@ict4personswithdisabilities.org
- **Technical Support**: [8 Technologies Consultants](https://8technologies.net/)
- **Project Website**: [ICT4PWDs](https://app.ict4personswithdisabilities.org/)

---

*This platform represents a significant step toward digital inclusion for persons with disabilities in Uganda, fostering independence, employment, and social participation through technology.*
