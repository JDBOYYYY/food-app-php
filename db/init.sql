-- Utworzenie bazy danych
PRINT 'Rozpoczęcie inicjalizacji bazy danych...'
GO
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'MyFoodApiDb')
BEGIN
    CREATE DATABASE MyFoodApiDb;
END
GO

USE MyFoodApiDb;
GO
PRINT 'Tworzenie tabel...'
GO

-- Tabele ASP.NET Core Identity z kluczami INT --

-- Tabela AspNetRoles
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetRoles]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetRoles](
    [Id] [int] IDENTITY(1,1) NOT NULL,
    [Name] [nvarchar](256) NULL,
    [NormalizedName] [nvarchar](256) NULL,
    [ConcurrencyStamp] [nvarchar](max) NULL,
    CONSTRAINT [PK_AspNetRoles] PRIMARY KEY CLUSTERED ([Id] ASC)
    );
CREATE UNIQUE NONCLUSTERED INDEX [RoleNameIndex] ON [dbo].[AspNetRoles] ([NormalizedName] ASC) WHERE ([NormalizedName] IS NOT NULL);
PRINT 'Utworzono tabelę AspNetRoles.'
END
GO

-- Tabela AspNetUsers (zastępuje oryginalną tabelę Users)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetUsers]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetUsers](
    [Id] [int] IDENTITY(1,1) NOT NULL,
    [Name] [nvarchar](100) NOT NULL, -- Dodana niestandardowa kolumna Name
    [UserName] [nvarchar](256) NULL,
    [NormalizedUserName] [nvarchar](256) NULL,
    [Email] [nvarchar](256) NULL,
    [NormalizedEmail] [nvarchar](256) NULL,
    [EmailConfirmed] [bit] NOT NULL,
    [PasswordHash] [nvarchar](max) NULL,
    [SecurityStamp] [nvarchar](max) NULL,
    [ConcurrencyStamp] [nvarchar](max) NULL,
    [PhoneNumber] [nvarchar](max) NULL,
    [PhoneNumberConfirmed] [bit] NOT NULL,
    [TwoFactorEnabled] [bit] NOT NULL,
    [LockoutEnd] [datetimeoffset](7) NULL,
    [LockoutEnabled] [bit] NOT NULL,
    [AccessFailedCount] [int] NOT NULL,
    CONSTRAINT [PK_AspNetUsers] PRIMARY KEY CLUSTERED ([Id] ASC)
    );
CREATE NONCLUSTERED INDEX [EmailIndex] ON [dbo].[AspNetUsers] ([NormalizedEmail] ASC);
CREATE UNIQUE NONCLUSTERED INDEX [UserNameIndex] ON [dbo].[AspNetUsers] ([NormalizedUserName] ASC) WHERE ([NormalizedUserName] IS NOT NULL);
PRINT 'Utworzono tabelę AspNetUsers.'
END
GO

-- Tabela AspNetRoleClaims
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetRoleClaims]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetRoleClaims](
    [Id] [int] IDENTITY(1,1) NOT NULL,
    [RoleId] [int] NOT NULL,
    [ClaimType] [nvarchar](max) NULL,
    [ClaimValue] [nvarchar](max) NULL,
    CONSTRAINT [PK_AspNetRoleClaims] PRIMARY KEY CLUSTERED ([Id] ASC),
    CONSTRAINT [FK_AspNetRoleClaims_AspNetRoles_RoleId] FOREIGN KEY([RoleId]) REFERENCES [dbo].[AspNetRoles] ([Id]) ON DELETE CASCADE
    );
CREATE NONCLUSTERED INDEX [IX_AspNetRoleClaims_RoleId] ON [dbo].[AspNetRoleClaims] ([RoleId] ASC);
PRINT 'Utworzono tabelę AspNetRoleClaims.'
END
GO

-- Tabela AspNetUserClaims
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetUserClaims]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetUserClaims](
    [Id] [int] IDENTITY(1,1) NOT NULL,
    [UserId] [int] NOT NULL,
    [ClaimType] [nvarchar](max) NULL,
    [ClaimValue] [nvarchar](max) NULL,
    CONSTRAINT [PK_AspNetUserClaims] PRIMARY KEY CLUSTERED ([Id] ASC),
    CONSTRAINT [FK_AspNetUserClaims_AspNetUsers_UserId] FOREIGN KEY([UserId]) REFERENCES [dbo].[AspNetUsers] ([Id]) ON DELETE CASCADE
    );
CREATE NONCLUSTERED INDEX [IX_AspNetUserClaims_UserId] ON [dbo].[AspNetUserClaims] ([UserId] ASC);
PRINT 'Utworzono tabelę AspNetUserClaims.'
END
GO

-- Tabela AspNetUserLogins
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetUserLogins]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetUserLogins](
    [LoginProvider] [nvarchar](450) NOT NULL,
    [ProviderKey] [nvarchar](450) NOT NULL,
    [ProviderDisplayName] [nvarchar](max) NULL,
    [UserId] [int] NOT NULL,
    CONSTRAINT [PK_AspNetUserLogins] PRIMARY KEY CLUSTERED ([LoginProvider] ASC, [ProviderKey] ASC),
    CONSTRAINT [FK_AspNetUserLogins_AspNetUsers_UserId] FOREIGN KEY([UserId]) REFERENCES [dbo].[AspNetUsers] ([Id]) ON DELETE CASCADE
    );
CREATE NONCLUSTERED INDEX [IX_AspNetUserLogins_UserId] ON [dbo].[AspNetUserLogins] ([UserId] ASC);
PRINT 'Utworzono tabelę AspNetUserLogins.'
END
GO

-- Tabela AspNetUserRoles
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetUserRoles]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetUserRoles](
    [UserId] [int] NOT NULL,
    [RoleId] [int] NOT NULL,
     CONSTRAINT [PK_AspNetUserRoles] PRIMARY KEY CLUSTERED ([UserId] ASC, [RoleId] ASC),
    CONSTRAINT [FK_AspNetUserRoles_AspNetRoles_RoleId] FOREIGN KEY([RoleId]) REFERENCES [dbo].[AspNetRoles] ([Id]) ON DELETE CASCADE,
    CONSTRAINT [FK_AspNetUserRoles_AspNetUsers_UserId] FOREIGN KEY([UserId]) REFERENCES [dbo].[AspNetUsers] ([Id]) ON DELETE CASCADE
    );
CREATE NONCLUSTERED INDEX [IX_AspNetUserRoles_RoleId] ON [dbo].[AspNetUserRoles] ([RoleId] ASC);
PRINT 'Utworzono tabelę AspNetUserRoles.'
END
GO

IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Admin')
BEGIN
INSERT INTO AspNetRoles (Name, NormalizedName, ConcurrencyStamp)
VALUES ('Admin', 'ADMIN', NEWID());
PRINT 'Wstawiono rolę Admin do AspNetRoles.'
END
GO

-- Tabela AspNetUserTokens
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[AspNetUserTokens]') AND type in (N'U'))
BEGIN
CREATE TABLE [dbo].[AspNetUserTokens](
    [UserId] [int] NOT NULL,
    [LoginProvider] [nvarchar](450) NOT NULL,
    [Name] [nvarchar](450) NOT NULL,
    [Value] [nvarchar](max) NULL,
    CONSTRAINT [PK_AspNetUserTokens] PRIMARY KEY CLUSTERED ([UserId] ASC, [LoginProvider] ASC, [Name] ASC),
    CONSTRAINT [FK_AspNetUserTokens_AspNetUsers_UserId] FOREIGN KEY([UserId]) REFERENCES [dbo].[AspNetUsers] ([Id]) ON DELETE CASCADE
    );
PRINT 'Utworzono tabelę AspNetUserTokens.'
END
GO

-- Tabele specyficzne dla aplikacji --

-- Tabela Restaurants
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Restaurants]') AND type in (N'U'))
BEGIN
CREATE TABLE Restaurants (
                             Id INT IDENTITY(1,1) PRIMARY KEY,
                             Name NVARCHAR(150) NOT NULL,
                             ImageUrl NVARCHAR(1024) NULL,
                             AverageRating FLOAT NULL,
                             DeliveryTime NVARCHAR(50) NULL,
                             Distance NVARCHAR(50) NULL,
                             PriceRange NVARCHAR(50) NULL
);
PRINT 'Utworzono tabelę Restaurants.'
END
GO

-- Tabela Categories
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Categories]') AND type in (N'U'))
BEGIN
CREATE TABLE Categories (
                            Id INT IDENTITY(1,1) PRIMARY KEY,
                            Name NVARCHAR(50) NOT NULL UNIQUE,
);
PRINT 'Utworzono tabelę Categories.'
END
GO

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[RestaurantCategories]') AND type in (N'U'))
BEGIN
CREATE TABLE RestaurantCategories (
                                      RestaurantId INT NOT NULL,
                                      CategoryId INT NOT NULL,
                                      CONSTRAINT PK_RestaurantCategories PRIMARY KEY (RestaurantId, CategoryId),
                                      CONSTRAINT FK_RestaurantCategories_Restaurants FOREIGN KEY (RestaurantId) REFERENCES Restaurants(Id) ON DELETE CASCADE,
                                      CONSTRAINT FK_RestaurantCategories_Categories FOREIGN KEY (CategoryId) REFERENCES Categories(Id) ON DELETE CASCADE
);
PRINT 'Utworzono tabelę RestaurantCategories.'
END
GO



-- Tabela Addresses (zaktualizowany klucz obcy)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Addresses]') AND type in (N'U'))
BEGIN
CREATE TABLE Addresses (
                           Id INT IDENTITY(1,1) PRIMARY KEY,
                           Street NVARCHAR(200) NOT NULL,
                           Apartment NVARCHAR(50),
                           City NVARCHAR(100) NOT NULL,
                           PostalCode NVARCHAR(20) NOT NULL,
                           Country NVARCHAR(100) NOT NULL,
                           UserId INT NOT NULL,
                           CONSTRAINT FK_Addresses_AspNetUsers FOREIGN KEY (UserId) -- Zaktualizowana nazwa i cel klucza obcego
                               REFERENCES AspNetUsers(Id) ON DELETE CASCADE
);
PRINT 'Utworzono tabelę Addresses.'
END
GO

-- Tabela Products
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Products]') AND type in (N'U'))
BEGIN
CREATE TABLE Products (
                          Id INT IDENTITY(1,1) PRIMARY KEY,
                          Name NVARCHAR(100) NOT NULL,
                          Description NVARCHAR(MAX),
                          Price DECIMAL(18,2) NOT NULL,
                          RestaurantId INT NOT NULL,
                          CategoryId INT NOT NULL,
                          ImageUrl NVARCHAR(1024) NULL,
                          CONSTRAINT FK_Products_Restaurants FOREIGN KEY (RestaurantId)
                              REFERENCES Restaurants(Id) ON DELETE CASCADE,
                          CONSTRAINT FK_Products_Categories FOREIGN KEY (CategoryId)
                              REFERENCES Categories(Id)
);
PRINT 'Utworzono tabelę Products.'
END
GO


-- Tabela Orders (zaktualizowany klucz obcy)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Orders]') AND type in (N'U'))
BEGIN
CREATE TABLE Orders (
                        Id INT IDENTITY(1,1) PRIMARY KEY,
                        OrderDate DATETIME NOT NULL DEFAULT GETDATE(),
                        TotalAmount DECIMAL(18,2) NOT NULL,
                        Status NVARCHAR(50) NOT NULL DEFAULT 'Pending',
                        UserId INT NOT NULL,
                        RestaurantId INT NOT NULL,
                        DeliveryAddressId INT,
                        CONSTRAINT FK_Orders_AspNetUsers FOREIGN KEY (UserId) -- Zaktualizowana nazwa i cel klucza obcego
                            REFERENCES AspNetUsers(Id), -- Usunięto ON DELETE CASCADE dla Orders, zdecyduj czy potrzebne
                        CONSTRAINT FK_Orders_Restaurants FOREIGN KEY (RestaurantId)
                            REFERENCES Restaurants(Id),
                        CONSTRAINT FK_Orders_Addresses FOREIGN KEY (DeliveryAddressId)
                            REFERENCES Addresses(Id) ON DELETE SET NULL
);
PRINT 'Utworzono tabelę Orders.'
END
GO

-- Tabela OrderItems
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[OrderItems]') AND type in (N'U'))
BEGIN
CREATE TABLE OrderItems (
                            Id INT IDENTITY(1,1) PRIMARY KEY,
                            Quantity INT NOT NULL,
                            UnitPrice DECIMAL(18,2) NOT NULL,
                            OrderId INT NOT NULL,
                            ProductId INT NOT NULL,
                            CONSTRAINT CHK_Quantity CHECK (Quantity > 0),
                            CONSTRAINT FK_OrderItems_Orders FOREIGN KEY (OrderId)
                                REFERENCES Orders(Id) ON DELETE CASCADE,
                            CONSTRAINT FK_OrderItems_Products FOREIGN KEY (ProductId)
                                REFERENCES Products(Id)
);
PRINT 'Utworzono tabelę OrderItems.'
END
GO

-- Tabela Payments
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Payments]') AND type in (N'U'))
BEGIN
CREATE TABLE Payments (
                          OrderId INT PRIMARY KEY,
                          Amount DECIMAL(18,2) NOT NULL,
                          PaymentDate DATETIME NOT NULL DEFAULT GETDATE(),
                          PaymentMethod NVARCHAR(50) NOT NULL,
                          Status NVARCHAR(50) NOT NULL,
                          CONSTRAINT FK_Payments_Orders FOREIGN KEY (OrderId)
                              REFERENCES Orders(Id) ON DELETE CASCADE
);
PRINT 'Utworzono tabelę Payments.'
END
GO

-- Tabela Reviews (zaktualizowany klucz obcy)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Reviews]') AND type in (N'U'))
BEGIN
CREATE TABLE Reviews (
                         Id INT IDENTITY(1,1) PRIMARY KEY,
                         Rating INT NOT NULL,
                         Comment NVARCHAR(1000),
                         ReviewDate DATETIME NOT NULL DEFAULT GETDATE(),
                         UserId INT NOT NULL,
                         RestaurantId INT,
                         ProductId INT,
                         CONSTRAINT CHK_Rating CHECK (Rating >= 1 AND Rating <= 5),
                         CONSTRAINT CHK_Review_Target CHECK (
                             (RestaurantId IS NOT NULL AND ProductId IS NULL) OR
                             (RestaurantId IS NULL AND ProductId IS NOT NULL)
                             ),
                         CONSTRAINT FK_Reviews_AspNetUsers FOREIGN KEY (UserId) -- Zaktualizowana nazwa i cel klucza obcego
                             REFERENCES AspNetUsers(Id) ON DELETE CASCADE,
                         CONSTRAINT FK_Reviews_Restaurants FOREIGN KEY (RestaurantId)
                             REFERENCES Restaurants(Id),
                         CONSTRAINT FK_Reviews_Products FOREIGN KEY (ProductId)
                             REFERENCES Products(Id)
);
PRINT 'Utworzono tabelę Reviews.'
END
GO

-- Tabela Favorites (zaktualizowany klucz obcy)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[Favorites]') AND type in (N'U'))
BEGIN
CREATE TABLE Favorites (
                           Id INT IDENTITY(1,1) PRIMARY KEY,
                           UserId INT NOT NULL,
                           RestaurantId INT,
                           ProductId INT,
                           CONSTRAINT CHK_Favorite_Target CHECK (
                               (RestaurantId IS NOT NULL AND ProductId IS NULL) OR
                               (RestaurantId IS NULL AND ProductId IS NOT NULL)
                               ),
                           CONSTRAINT FK_Favorites_AspNetUsers FOREIGN KEY (UserId) -- Zaktualizowana nazwa i cel klucza obcego
                               REFERENCES AspNetUsers(Id) ON DELETE CASCADE,
                           CONSTRAINT FK_Favorites_Restaurants FOREIGN KEY (RestaurantId)
                               REFERENCES Restaurants(Id),
                           CONSTRAINT FK_Favorites_Products FOREIGN KEY (ProductId)
                               REFERENCES Products(Id)
);
PRINT 'Utworzono tabelę Favorites.'
END
GO

-- Utworzenie unikalnych indeksów dla Favorites
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'UQ_Favorite_User_Restaurant' AND object_id = OBJECT_ID('Favorites'))
BEGIN
CREATE UNIQUE INDEX UQ_Favorite_User_Restaurant
    ON Favorites (UserId, RestaurantId)
    WHERE RestaurantId IS NOT NULL;
END
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'UQ_Favorite_User_Product' AND object_id = OBJECT_ID('Favorites'))
BEGIN
CREATE UNIQUE INDEX UQ_Favorite_User_Product
    ON Favorites (UserId, ProductId)
    WHERE ProductId IS NOT NULL;
END
GO

PRINT 'Zakończono tworzenie tabel.'
GO

PRINT 'Wstawianie przykładowych danych ze statycznymi ID...'
GO

-- Użytkownicy (statyczne ID 1-5)
SET IDENTITY_INSERT AspNetUsers ON;
INSERT INTO AspNetUsers (Id, Name, UserName, NormalizedUserName, Email, NormalizedEmail, EmailConfirmed, PasswordHash, SecurityStamp, ConcurrencyStamp, PhoneNumber, PhoneNumberConfirmed, TwoFactorEnabled, LockoutEnd, LockoutEnabled, AccessFailedCount)
VALUES
    (1, 'Alice Smith', 'alice@example.com', 'ALICE@EXAMPLE.COM', 'alice@example.com', 'ALICE@EXAMPLE.COM', 1, NULL, NEWID(), NEWID(), NULL, 0, 0, NULL, 1, 0),
    (2, 'Bob Johnson', 'bob@example.com', 'BOB@EXAMPLE.COM', 'bob@example.com', 'BOB@EXAMPLE.COM', 1, NULL, NEWID(), NEWID(), NULL, 0, 0, NULL, 1, 0),
    (3, 'Charlie Lee', 'charlie@example.com', 'CHARLIE@EXAMPLE.COM', 'charlie@example.com', 'CHARLIE@EXAMPLE.COM', 1, NULL, NEWID(), NEWID(), NULL, 0, 0, NULL, 1, 0),
    (4, 'Diana Prince', 'diana@example.com', 'DIANA@EXAMPLE.COM', 'diana@example.com', 'DIANA@EXAMPLE.COM', 1, NULL, NEWID(), NEWID(), NULL, 0, 0, NULL, 1, 0),
    (5, 'Eve Adams', 'eve@example.com', 'EVE@EXAMPLE.COM', 'eve@example.com', 'EVE@EXAMPLE.COM', 1, NULL, NEWID(), NEWID(), NULL, 0, 0, NULL, 1, 0);
SET IDENTITY_INSERT AspNetUsers OFF;
GO

SET IDENTITY_INSERT Categories ON;
-- Kategorie
INSERT INTO Categories (Id, Name) VALUES
                                      (1, N'Śniadania'),
                                      (2, N'Kanapki'),
                                      (3, N'Przystawki'),
                                      (4, N'Tortilla'),
                                      (5, N'Burgery');
GO
SET IDENTITY_INSERT Categories OFF;

-- Restauracje
SET IDENTITY_INSERT Restaurants ON;
INSERT INTO Restaurants (Id, Name, ImageUrl, AverageRating, DeliveryTime, Distance, PriceRange) VALUES
                                                                                                    (1, 'Pizza Palace', 'https://imageproxy.wolt.com/assets/673208e6cc36c63296339a84?w=1920', 4.5, '30-40 min', '2.5 km', '$$'),
                                                                                                    (2, 'Sushi Central', 'https://imageproxy.wolt.com/venue/638db5c9ab4f5f7ee10f2d73/c48668a8-9317-11ed-9f71-7e684ff7b3a6_29d55d26_79f6_11ed_8cf8_72d20c7898d1_new_koku_9_photoroom.jpg', 4.7, '40-50 min', '3.1 km', '$$$'),
                                                                                                    (3, 'Dosyta.pl - Kuchnia Indyjska, europejska, makarony, śniadania', 'https://imageproxy.wolt.com/venue/650ac69923e9e66f563fe792/1876e9b6-8cf4-11ee-84fa-2ea8843c5713_ad315e3e_62aa_11ee_8c1c_7a4e0527b982_image_50729217.jpg', 4.8, '30-45 min', '2.8 km', '$$'),
                                                                                                    (4, 'Grill Kebab', 'https://imageproxy.wolt.com/venue/63904af2cec8c1d4f23b53bf/22ab3266-779c-11ed-965f-caab856315a3_2018f7b6_25b6_11eb_b3a5_0efa88759ff4_lolek2.jpg', 5.0, '25-35 min', '3.5 km', '$'),
                                                                                                    (5, 'Raj Menu Bez Glutenu', 'https://imageproxy.wolt.com/venue/638f279e2cfd52310e2509a4/0e6e1c38-7b92-11ed-8b8f-06b220bace18_projekt_bez_tytu_u___2022_12_14t103105.513.png', 4.6, '20-30 min', '2.2 km', '$$'),
                                                                                                    (6, 'McDonald''s - Galeria Jurowiecka', 'https://imageproxy.wolt.com/mes-image/27ed7f86-4841-474d-bb78-66a06f7a781d/b5a9f02a-8392-4345-bfaa-e19d057b8b68', 1.4, '25-35 min', '2.9 km', '$');
GO
SET IDENTITY_INSERT Restaurants OFF;

-- RestaurantCategories
INSERT INTO RestaurantCategories (RestaurantId, CategoryId) VALUES
                                                                (3, 1), (3, 2), (3, 3), -- Dosyta.pl
                                                                (4, 4),                 -- Grill Kebab
                                                                (5, 1),                 -- Raj Menu
                                                                (6, 5);                 -- McDonald's
GO

SET IDENTITY_INSERT Products ON;
INSERT INTO Products (Id, Name, Description, Price, RestaurantId, CategoryId, ImageUrl) VALUES
-- Dosyta.pl - Śniadania
(101, N'* Śniadanie klasyczne', N'Obfity początek dnia z jajkami, kiełbaską i tostami.', 24.00, 3, 1, 'https://imageproxy.wolt.com/menu/menu-images/6516e13c77adb2ed4c3074fe/e8341ae2-6117-11ee-a27a-8aa008711458_s_niadanie_klasyczne_photoroom.png'),
(102, N'Szakszuka z chorizo', N'Pikantne zapiekane jajka w sosie pomidorowym z chorizo.', 36.00, 3, 1, 'https://imageproxy.wolt.com/menu/menu-images/6516920248b690584d374f80/d358cf26-2973-11ef-a005-e297c0d707b7_szakszuka.jpg'),
(103, N'* Śniadanie angielskie', N'Klasyczne angielskie śniadanie ze wszystkimi dodatkami.', 41.00, 3, 1, 'https://imageproxy.wolt.com/menu/menu-images/6516e13c77adb2ed4c3074fe/30786510-6118-11ee-8aa8-fabe49f12502_s_niadanie_angielskie_photoroom.png'),

-- Dosyta.pl - Kanapki
(104, N'Kanapka Norweska', N'Wędzony łosoś i świeży koperek na tostowanej bułce.', 32.00, 3, 2, 'https://imageproxy.wolt.com/menu/menu-images/6516920248b690584d374f80/4ff6ce8c-7cba-11ee-beef-ba2fb944ad25_projekt_bez_nazwy__34_.png'),
(105, N'Kanapka wiejska', N'Obfita kanapka w stylu wiejskim z szynką i serem.', 24.00, 3, 2, 'https://imageproxy.wolt.com/menu/menu-images/6516920248b690584d374f80/8843054e-7cba-11ee-a839-fe58f4758757_projekt_bez_nazwy__35_.png'),
(106, N'Kanapka amerykańska', N'Amerykańska kanapka z indykiem i serem provolone.', 30.00, 3, 2, 'https://imageproxy.wolt.com/menu/menu-images/6516920248b690584d374f80/380eabe4-7cbc-11ee-a835-9a1f52dacaca_projekt_bez_nazwy__32_.png'),

-- Dosyta.pl - Przystawki europejskie
(107, N'Krążki cebulowe z sosem', N'Chrupiące smażone krążki cebulowe z sosem.', 17.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/53ec796a-60f9-11ee-8f9a-ee322d846b0a_kra_z_ki_cebulowe_photoroom.png'),
(108, N'Frytki z batatów z sosem', N'Frytki z batatów przyprawione do perfekcji.', 20.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/0951eeb2-60f9-11ee-9976-ee322d846b0a_frytki_z_batata_photoroom.png'),
(109, N'Domowa fryta', N'Domowe frytki, złociste i pyszne.', 21.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/6516920248b690584d374f80/3538f15a-7cb1-11ee-8c65-3a5bfc30e836_projekt_bez_nazwy__26_.png'),
(110, N'Kalmary z sosem', N'Smażone krążki kalmarów z pikantnym sosem.', 26.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/442fde2c-60f9-11ee-bd98-6e96efc2b2f7_kalmary_photoroom.png'),
(111, N'Tatar wołowy', N'Surowa wołowina z dodatkami.', 41.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/e8e228c2-60fd-11ee-89dd-66131b00b05d_tatar_wo_owy_photoroom.png'),
(112, N'Krewetki w sosie maślano-winnym', N'Krewetki w sosie maślano-winnym.', 41.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/b76f3fdc-60fd-11ee-9c08-a656e123f15b_krewetki_w_sosie_mas_lano_winnym_photoroom.png'),
(113, N'Deska Podlaska', N'Sery i wędliny z Podlasia.', 77.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/de734498-6819-11ee-92e0-26c0a80c6cef_deska_ser_w.jpeg'),
(114, N'Zestaw do piwa', N'Mieszanka przekąsek do piwa.', 71.00, 3, 3, 'https://imageproxy.wolt.com/menu/menu-images/651689acf7a491e41eacf051/f4017cd0-6819-11ee-ac61-7a9c33c4af1c_przek_ski_grill.jpeg'),

-- Grill Kebab - Tortilla
(201, N'Tortilla z kurczakiem', N'Tortilla z kurczakiem i warzywami.', 29.00, 4, 4, 'https://imageproxy.wolt.com/menu/menu-images/639085718bc45e39e06e8df4/32763f76-779a-11ed-a2d4-8229df6cd75b_9524373_grillkebab_food_tortillazkurczakiem_4x3_photoroom.jpeg'),
(202, N'Tortilla z kotletem szpinakowo-serowym 🌿', N'Tortilla wege z kotletem ze szpinaku i sera.', 28.00, 4, 4, 'https://imageproxy.wolt.com/menu/menu-images/639085718bc45e39e06e8df4/3bd804d2-779a-11ed-abe4-6235cd2f36e2_9524373_grillkebab_food_tortillazkotletemszpinakowoserowym_4x3_photoroom.jpeg'),
(203, N'Tortilla grecka', N'Tortilla z fetą i oliwkami.', 32.00, 4, 4, 'https://imageproxy.wolt.com/menu/menu-images/639085718bc45e39e06e8df4/608c6fac-779a-11ed-8dbb-3e4c0f5b5061_9524373_grillkebab_food_tortillagrecka_4x3_photoroom.jpeg'),
(204, N'Tortilla samo mięso', N'Tylko mięso kebab w tortilli.', 33.00, 4, 4, 'https://imageproxy.wolt.com/menu/menu-images/639085718bc45e39e06e8df4/435f10a6-779a-11ed-8b27-3e5f629b5bea_9524373_grillkebab_food_tortillasamomie_so_4x3_photoroom.jpeg');

INSERT INTO Products (Id, Name, Description, Price, RestaurantId, CategoryId, ImageUrl) VALUES
                                                                                            (301, N'Jajecznica', N'Pyszna, puszysta jajecznica przygotowana na Twoje zamówienie.', 31.00, 5, 1, 'https://imageproxy.wolt.com/assets/675aa3f251c8315e489ee3d5'),
                                                                                            (302, N'Zestaw wegetariański', N'Śniadanie pełne smaków warzyw i innych pysznych, bezglutenowych składników.', 32.00, 5, 1, 'https://imageproxy.wolt.com/assets/675aa48a51c8315e489ee3de'),
                                                                                            (303, N'Tofucznica', N'Bezglutenowa i wegańska alternatywa dla jajecznicy, przygotowana z tofu.', 32.00, 5, 1, 'https://imageproxy.wolt.com/assets/675aa48a51c8315e489ee3e1'),
                                                                                            (304, N'Szakszuka', N'Aromatyczna szakszuka z pomidorami i jajkami (lub tofu w wersji wegańskiej), idealna na śniadanie.', 36.00, 5, 1, 'https://imageproxy.wolt.com/assets/675aa48a51c8315e489ee3e0');

INSERT INTO Products (Id, Name, Description, Price, RestaurantId, CategoryId, ImageUrl) VALUES
    (401, N'Burger Drwala', N'Burger z soczystym kotletem, serem i chrupiącym bekonem.', 29.60, 6, 5, 'https://imageproxy.wolt.com/mes-image/27ed7f86-4841-474d-bb78-66a06f7a781d/b5a9f02a-8392-4345-bfaa-e19d057b8b68');

SET IDENTITY_INSERT Products OFF;

SET IDENTITY_INSERT Addresses ON;
INSERT INTO Addresses (Id, Street, Apartment, City, PostalCode, Country, UserId)
VALUES
    (1, N'ul. Zielona', N'12A', N'Warszawa', N'00-001', N'Polska', 1),
    (2, N'ul. Kwiatowa', N'8B', N'Kraków', N'30-002', N'Polska', 2);
SET IDENTITY_INSERT Addresses OFF;

SET IDENTITY_INSERT Orders ON;
-- Orders
INSERT INTO Orders (Id, OrderDate, TotalAmount, Status, UserId, RestaurantId, DeliveryAddressId) VALUES
                                                                                                     (1, GETDATE(), 29.00, 'Completed', 1, 4, 1), -- Grill Kebab
                                                                                                     (2, GETDATE(), 24.00, 'Pending', 2, 3, 2);   -- Dosyta.pl
GO
SET IDENTITY_INSERT Orders OFF;

SET IDENTITY_INSERT OrderItems ON;
-- OrderItems
INSERT INTO OrderItems (Id, Quantity, UnitPrice, OrderId, ProductId) VALUES
                                                                         (1, 1, 29.00, 1, 201), -- Tortilla
                                                                         (2, 1, 24.00, 2, 101); -- Śniadanie klasyczne
GO
SET IDENTITY_INSERT OrderItems OFF;

-- Payments
INSERT INTO Payments (OrderId, Amount, PaymentDate, PaymentMethod, Status) VALUES
                                                                               (1, 29.00, GETDATE(), 'Card', 'Completed'),
                                                                               (2, 24.00, GETDATE(), 'Blik', 'Pending');
GO

SET IDENTITY_INSERT Reviews ON;
-- Reviews
INSERT INTO Reviews (Id, Rating, Comment, ReviewDate, UserId, RestaurantId, ProductId) VALUES
                                                                                           (1, 4, N'Smaczne i szybkie', GETDATE(), 3, 4, NULL),
                                                                                           (2, 5, N'Pyszne śniadanie!', GETDATE(), 4, NULL, 101);
GO
SET IDENTITY_INSERT Reviews OFF;

SET IDENTITY_INSERT Favorites ON;
-- Favorites
INSERT INTO Favorites (Id, UserId, RestaurantId, ProductId) VALUES
                                                                (1, 1, 3, NULL),
                                                                (2, 2, NULL, 201);
GO
SET IDENTITY_INSERT Favorites OFF;

PRINT 'Wstawianie przykładowych danych zakończone.'
GO

