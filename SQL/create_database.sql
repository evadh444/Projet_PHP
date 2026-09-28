-- Début du script copié/collé de celui de Quentin pour la biblio --
USE master;


GO
-- Le script est réexécutable : on repart d'une base propre.
-- SINGLE_USER ... ROLLBACK IMMEDIATE ferme les connexions en cours,
-- sans quoi le DROP échouerait.
IF DB_ID('henna_pdo') IS NOT NULL
    BEGIN
        ALTER DATABASE henna_pdo
            SET SINGLE_USER 
            WITH ROLLBACK IMMEDIATE;
        DROP DATABASE henna_pdo;
    END


GO
CREATE DATABASE henna_pdo;


GO
/* -----------------------------------------------------------------------------
   2. Connexion (login) et utilisateur (user)
   -----------------------------------------------------------------------------
   Différence essentielle avec MySQL : SQL Server sépare deux notions.

     LOGIN  -> niveau SERVEUR   : permet de se connecter à l'instance
     USER   -> niveau BASE      : permet d'accéder à une base précise

   Un LOGIN sans USER peut se connecter mais ne voit aucune base.
   -------------------------------------------------------------------------- */
USE master;


GO
IF SUSER_ID('henna_user') IS NOT NULL
    DROP LOGIN henna_user;


GO
CREATE LOGIN henna_user
    WITH PASSWORD = 'Test1234=', DEFAULT_DATABASE = henna_pdo, CHECK_POLICY = OFF;


GO
USE henna_pdo;


GO
CREATE USER henna_user FOR LOGIN henna_user;


GO
-- Droits minimaux : lecture + écriture sur les données.
-- On évite db_owner, qui donnerait tous les droits (dont DROP TABLE).
ALTER ROLE db_datareader ADD MEMBER henna_user;

ALTER ROLE db_datawriter ADD MEMBER henna_user;


GO

-- Script copié/collé via SSMS car tables faites à la main (GenerateScript) --
-- Puis ajout des valeurs dans les tableaux direct dans le script
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Atelier](
	[AtelierId] [int] IDENTITY(1,1) NOT NULL,
	[nomAtelier] [nvarchar](50) NOT NULL,
	[description] [nvarchar](max) NULL,
	[lieu] [nvarchar](50) NOT NULL,
	[date] [datetime2](7) NOT NULL,
	[heure] [time](7) NOT NULL,
	[prix] [decimal](10, 2) NOT NULL,
	[nb_participants] [int] NOT NULL,
	[places_restantes] [int] NULL,
	[UserId] [int] NOT NULL,
	[estActif] [bit] NOT NULL,
 CONSTRAINT [PK_Atelier] PRIMARY KEY CLUSTERED 
(
	[AtelierId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Inscription]    Script Date: 25/09/2026 13:40:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Inscription](
	[InscriptionId] [int] IDENTITY(1,1) NOT NULL,
	[date_inscription] [datetime2](7) NOT NULL,
	[AtelierId] [int] NOT NULL,
	[UserId] [int] NOT NULL,
	[estActif] [bit] NOT NULL,
 CONSTRAINT [PK_Inscription] PRIMARY KEY CLUSTERED 
(
	[InscriptionId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Prestation]    Script Date: 25/09/2026 13:40:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Prestation](
	[PrestationId] [int] IDENTITY(1,1) NOT NULL,
	[nomPrestation] [nvarchar](100) NOT NULL,
	[description] [nvarchar](max) NULL,
	[prix] [decimal](10, 2) NOT NULL,
	[dureeMinute] [int] NOT NULL,
	[UserId] [int] NOT NULL,
	[estActif] [bit] NOT NULL,
 CONSTRAINT [PK_Prestation] PRIMARY KEY CLUSTERED 
(
	[PrestationID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Reservation]    Script Date: 25/09/2026 13:40:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Reservation](
	[ReservationId] [int] IDENTITY(1,1) NOT NULL,
	[dateReservation] [datetime2](7) NOT NULL,
	[dateRdv] [datetime2](7) NOT NULL,
	[heureRdv] [time](7) NOT NULL,
	[commentaire] [nvarchar](max) NULL,
	[PrestationId] [int] NOT NULL,
	[UserId] [int] NOT NULL,
	[estActif] [bit] NOT NULL,
	[statut] [nvarchar](150) NOT NULL,
 CONSTRAINT [PK_Reservation] PRIMARY KEY CLUSTERED 
(
	[ReservationId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[User]    Script Date: 25/09/2026 13:40:52 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[User](
	[UserId] [int] IDENTITY(1,1) NOT NULL,
	[passwordHash] [nvarchar](200) NOT NULL,
	[nom] [nvarchar](100) NOT NULL,
	[prenom] [nvarchar](100) NOT NULL,
	[telephone] [nvarchar](30) NULL,
	[email] [nvarchar](100) NOT NULL,
	[role] [nvarchar](50) NOT NULL,
	[estActif] [bit] NOT NULL,
 CONSTRAINT [PK_User] PRIMARY KEY CLUSTERED 
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Index [IX_Atelier]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Atelier] ON [dbo].[Atelier]
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [IX_Inscription]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Inscription] ON [dbo].[Inscription]
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [IX_Inscription_1]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Inscription_1] ON [dbo].[Inscription]
(
	[AtelierId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [IX_Prestation]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Prestation] ON [dbo].[Prestation]
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [IX_Reservation_1]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Reservation_1] ON [dbo].[Reservation]
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
/****** Object:  Index [IX_Reservation_2]    Script Date: 25/09/2026 13:40:52 ******/
CREATE NONCLUSTERED INDEX [IX_Reservation_2] ON [dbo].[Reservation]
(
	[PrestationId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
GO
ALTER TABLE [dbo].[Atelier] ADD  CONSTRAINT [DF_Atelier_estActif]  DEFAULT ((1)) FOR [estActif]
GO
ALTER TABLE [dbo].[Inscription] ADD  CONSTRAINT [DF_Inscription_date_inscription]  DEFAULT (sysdatetime()) FOR [date_inscription]
GO
ALTER TABLE [dbo].[Inscription] ADD  CONSTRAINT [DF_Inscription_estActif]  DEFAULT ((1)) FOR [estActif]
GO
ALTER TABLE [dbo].[Prestation] ADD  CONSTRAINT [DF_Prestation_estActif]  DEFAULT ((1)) FOR [estActif]
GO
ALTER TABLE [dbo].[Reservation] ADD  CONSTRAINT [DF_Reservation_dateReservation]  DEFAULT (sysdatetime()) FOR [dateReservation]
GO
ALTER TABLE [dbo].[Reservation] ADD  CONSTRAINT [DF_Reservation_estActif]  DEFAULT ((1)) FOR [estActif]
GO
ALTER TABLE [dbo].[Reservation] ADD  CONSTRAINT [DF_Reservation_statut]  DEFAULT (N'EN ATTENTE') FOR [statut]
GO
ALTER TABLE [dbo].[User] ADD  CONSTRAINT [DF_User_role]  DEFAULT (N'CLIENT') FOR [role]
GO
ALTER TABLE [dbo].[User] ADD  CONSTRAINT [DF_User_estActif]  DEFAULT ((1)) FOR [estActif]
GO
USE [master]
GO
ALTER DATABASE [henna_pdo] SET  READ_WRITE 
GO

USE [henna_pdo]
GO

-- Données de Test
INSERT INTO [User]
	(passwordHash, nom, prenom, telephone, email, role, estActif)
VALUES
	(
	N'$2y$10$KIFgpOp0UEq.GVE3zUbg3upbK31Ozs.v5ez3q0FgNcDEtIEA8AV.S',
	N'Admin',
	N'Henna', 
	NULL,
	N'admin@henna.be',
	N'ADMIN',
	1
	);

INSERT INTO dbo.Prestation (
	nomPrestation,
	description,
	prix,
	dureeMinute,
	UserId
)
VALUES
('Henné main simple', 'Motif simple sur une main', 15.00, 15, 1),
('Henné main complète', 'Motif moyen sur les deux mains', 40.00, 40, 1),
('Henné mariage', 'Prestation complète pour mariée', 150.00, 120, 1);

GO

INSERT INTO dbo.Atelier (
	nomAtelier,
	date,
	heure,
	lieu,
	description,
	prix,
	nb_participants,
	places_restantes,
	UserId
)
VALUES
('Initiation au henné', '2026-10-12', '14:00:00', 'Parckfarm - Tour & Taxis', 'Découvertes des bases du henné', 25.00, 10, 4, 1),
('Faire mes tubes maison', '2026-04-11', '11:00:00', 'Maison de la Création', 'Fabrication de la pate de henné avec des ingrédients naturels & conception de tubes', 20.00, 5, 3, 1);

GO

-- Vérification

SELECT 
	nomPrestation AS 'Prestations',
	description,
	prix,
	dureeMinute AS 'Durée'
FROM Prestation;

SELECT
	nomAtelier AS 'Atelier',
	date,
	heure, 
	lieu,
	description,
	prix,
	nb_participants AS 'Nombre de place',
	places_restantes AS 'Places Restantes'
FROM Atelier;

SELECT
	UserId,
	nom,
	prenom,
	email,
	role,
	estActif
FROM [User];